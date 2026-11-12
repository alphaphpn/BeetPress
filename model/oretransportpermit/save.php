<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

if (!isset($_SESSION['d2s8wu_ustat']) || !isset($_SESSION['d2s8wu_verified']) || !isset($_SESSION['d2s8wu_xdel']) || !isset($_SESSION['d2s8wu_ulevel'])) {
	echo json_encode(array('status' => 'error', 'message' => 'Unauthorized'));
	exit;
}
if ($_SESSION['d2s8wu_ustat'] != 1 || $_SESSION['d2s8wu_verified'] != 1 || $_SESSION['d2s8wu_xdel'] != 0 || !in_array((int) $_SESSION['d2s8wu_ulevel'], array(1,2,15,16,17), true)) {
	echo json_encode(array('status' => 'error', 'message' => 'Unauthorized'));
	exit;
}

require_once dirname(__DIR__, 2) . '/lib/cnn.php';

function otp_null_date($value) {
	$value = trim((string) $value);
	if ($value === '') return null;
	$dt = DateTime::createFromFormat('Y-m-d', $value);
	return ($dt && $dt->format('Y-m-d') === $value) ? $value : null;
}

function otp_sanitize_filename_base($permitNo) {
	$base = trim($permitNo);
	// keep readable characters but remove path separators and dangerous chars
	$base = str_replace(array('\\', '/', ':', '*', '?', '"', '<', '>', '|'), '-', $base);
	$base = preg_replace('/\s+/', ' ', $base);
	return trim($base, " .\t\n\r\0\x0B-");
}

$permitId = isset($_POST['permit_autoid']) ? (int) $_POST['permit_autoid'] : 0;
$permitNo = trim((string) ($_POST['permit_no'] ?? ''));
$permitteeName = trim((string) ($_POST['permittee_name'] ?? ''));
$dateApproved = otp_null_date($_POST['date_approved'] ?? '');
$dateIssuance = otp_null_date($_POST['date_issuance'] ?? '');
$dateExpiry = otp_null_date($_POST['date_expiry'] ?? '');
$operatorName = trim((string) ($_POST['operator_name'] ?? ''));
$transporterName = trim((string) ($_POST['transporter_name'] ?? ''));
$truckRegistryNo = trim((string) ($_POST['truck_registry_no'] ?? ''));
$shipRegistryNo = trim((string) ($_POST['ship_registry_no'] ?? ''));
$buyerName = trim((string) ($_POST['buyer_name'] ?? ''));
$commodity = trim((string) ($_POST['commodity'] ?? ''));
$tonnageVolume = trim((string) ($_POST['tonnage_volume'] ?? ''));
$permitValueRaw = trim((string) ($_POST['permit_value'] ?? ''));
$sourceLocation = trim((string) ($_POST['source_location'] ?? ''));
$destinationLocation = trim((string) ($_POST['destination_location'] ?? ''));

if ($permitNo === '') {
	echo json_encode(array('status' => 'error', 'message' => 'Permit No. is required.'));
	exit;
}
if ($permitteeName === '') {
	echo json_encode(array('status' => 'error', 'message' => 'Name of Permittee is required.'));
	exit;
}

$permitValue = null;
if ($permitValueRaw !== '') {
	$normalized = str_replace(',', '', $permitValueRaw);
	if (!is_numeric($normalized)) {
		echo json_encode(array('status' => 'error', 'message' => 'Value must be a valid number.'));
		exit;
	}
	$permitValue = number_format((float) $normalized, 2, '.', '');
}

$fileBase = otp_sanitize_filename_base($permitNo);
if ($fileBase === '') {
	echo json_encode(array('status' => 'error', 'message' => 'Permit No. contains invalid characters for filename.'));
	exit;
}

try {
	$db = new myDatabase();
	$conn = $db->getConnection();
	$conn->exec("CREATE TABLE IF NOT EXISTS ore_transport_permit_tbl (
		permit_autoid INT AUTO_INCREMENT PRIMARY KEY,
		permit_no VARCHAR(191) NOT NULL,
		permittee_name TEXT NOT NULL,
		date_approved DATE NULL,
		date_issuance DATE NULL,
		date_expiry DATE NULL,
		operator_name TEXT NULL,
		transporter_name TEXT NULL,
		truck_registry_no TEXT NULL,
		ship_registry_no TEXT NULL,
		buyer_name TEXT NULL,
		commodity TEXT NULL,
		tonnage_volume TEXT NULL,
		permit_value DECIMAL(15,2) NULL,
		source_location TEXT NULL,
		destination_location TEXT NULL,
		permit_file VARCHAR(255) NULL,
		xdel INT(1) NOT NULL DEFAULT 0,
		createdby TEXT NULL,
		modifiedby TEXT NULL,
		modified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		UNIQUE KEY uniq_permit_no (permit_no)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	// duplicate check
	$dupSql = "SELECT permit_autoid FROM ore_transport_permit_tbl WHERE permit_no = :permit_no AND xdel = 0";
	if ($permitId > 0) $dupSql .= " AND permit_autoid <> :id";
	$dupSql .= " LIMIT 1";
	$dupStmt = $conn->prepare($dupSql);
	$dupStmt->bindParam(':permit_no', $permitNo);
	if ($permitId > 0) $dupStmt->bindParam(':id', $permitId, PDO::PARAM_INT);
	$dupStmt->execute();
	if ($dupStmt->fetch(PDO::FETCH_ASSOC)) {
		echo json_encode(array('status' => 'error', 'message' => 'Permit No. already exists. Duplicate permit numbers are not allowed.'));
		exit;
	}

	$user = trim((string) ($_SESSION['d2s8wu_uname'] ?? $_SESSION['d2s8wu_uid'] ?? 'System'));
	$existingFile = null;

	if ($permitId > 0) {
		$exStmt = $conn->prepare("SELECT permit_file FROM ore_transport_permit_tbl WHERE permit_autoid = :id AND xdel = 0 LIMIT 1");
		$exStmt->bindParam(':id', $permitId, PDO::PARAM_INT);
		$exStmt->execute();
		$existing = $exStmt->fetch(PDO::FETCH_ASSOC);
		if (!$existing) {
			echo json_encode(array('status' => 'error', 'message' => 'Permit record not found.'));
			exit;
		}
		$existingFile = $existing['permit_file'];
	}

	$permitFile = $existingFile;
	$uploadDir = dirname(__DIR__, 2) . '/public/ore/transport/permit/';
	if (!is_dir($uploadDir)) {
		@mkdir($uploadDir, 0755, true);
	}

	if (isset($_FILES['permit_file']) && isset($_FILES['permit_file']['error']) && $_FILES['permit_file']['error'] !== UPLOAD_ERR_NO_FILE) {
		if ($_FILES['permit_file']['error'] !== UPLOAD_ERR_OK) {
			echo json_encode(array('status' => 'error', 'message' => 'File upload failed.'));
			exit;
		}
		$originalName = (string) $_FILES['permit_file']['name'];
		$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
		if ($ext !== 'pdf') {
			echo json_encode(array('status' => 'error', 'message' => 'Only PDF files are allowed.'));
			exit;
		}
		$targetName = $fileBase . '.pdf';
		$targetPath = $uploadDir . $targetName;

		if (!move_uploaded_file($_FILES['permit_file']['tmp_name'], $targetPath)) {
			echo json_encode(array('status' => 'error', 'message' => 'Unable to save uploaded file.'));
			exit;
		}

		// if edited and old file had different name, remove old to avoid orphan mismatch
		if ($existingFile && $existingFile !== $targetName) {
			$oldPath = $uploadDir . basename($existingFile);
			if (is_file($oldPath)) @unlink($oldPath);
		}
		$permitFile = $targetName;
	}

	if ($permitId > 0) {
		$sql = "UPDATE ore_transport_permit_tbl SET
			permit_no = :permit_no,
			permittee_name = :permittee_name,
			date_approved = :date_approved,
			date_issuance = :date_issuance,
			date_expiry = :date_expiry,
			operator_name = :operator_name,
			transporter_name = :transporter_name,
			truck_registry_no = :truck_registry_no,
			ship_registry_no = :ship_registry_no,
			buyer_name = :buyer_name,
			commodity = :commodity,
			tonnage_volume = :tonnage_volume,
			permit_value = :permit_value,
			source_location = :source_location,
			destination_location = :destination_location,
			permit_file = :permit_file,
			modifiedby = :modifiedby
			WHERE permit_autoid = :id AND xdel = 0";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':id', $permitId, PDO::PARAM_INT);
	} else {
		$sql = "INSERT INTO ore_transport_permit_tbl (
			permit_no, permittee_name, date_approved, date_issuance, date_expiry,
			operator_name, transporter_name, truck_registry_no, ship_registry_no,
			buyer_name, commodity, tonnage_volume, permit_value, source_location,
			destination_location, permit_file, xdel, createdby, modifiedby
		) VALUES (
			:permit_no, :permittee_name, :date_approved, :date_issuance, :date_expiry,
			:operator_name, :transporter_name, :truck_registry_no, :ship_registry_no,
			:buyer_name, :commodity, :tonnage_volume, :permit_value, :source_location,
			:destination_location, :permit_file, 0, :createdby, :modifiedby
		)";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':createdby', $user);
	}

	$stmt->bindParam(':permit_no', $permitNo);
	$stmt->bindParam(':permittee_name', $permitteeName);
	$stmt->bindParam(':date_approved', $dateApproved);
	$stmt->bindParam(':date_issuance', $dateIssuance);
	$stmt->bindParam(':date_expiry', $dateExpiry);
	$stmt->bindParam(':operator_name', $operatorName);
	$stmt->bindParam(':transporter_name', $transporterName);
	$stmt->bindParam(':truck_registry_no', $truckRegistryNo);
	$stmt->bindParam(':ship_registry_no', $shipRegistryNo);
	$stmt->bindParam(':buyer_name', $buyerName);
	$stmt->bindParam(':commodity', $commodity);
	$stmt->bindParam(':tonnage_volume', $tonnageVolume);
	$stmt->bindParam(':permit_value', $permitValue);
	$stmt->bindParam(':source_location', $sourceLocation);
	$stmt->bindParam(':destination_location', $destinationLocation);
	$stmt->bindParam(':permit_file', $permitFile);
	$stmt->bindParam(':modifiedby', $user);
	$stmt->execute();

	$isCreate = ($permitId <= 0);
	$newId = $permitId > 0 ? $permitId : (int) $conn->lastInsertId();
	echo json_encode(array(
		'status' => 'success',
		'action' => $isCreate ? 'created' : 'updated',
		'message' => $isCreate ? 'Permit added successfully.' : 'Permit updated successfully.',
		'permit_autoid' => $newId,
		'permit_no' => $permitNo,
		'permit_file' => $permitFile
	));
} catch (Exception $e) {
	echo json_encode(array('status' => 'error', 'message' => 'Unable to save permit. ' . $e->getMessage()));
}
?>