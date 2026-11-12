<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

if (!isset($_SESSION['d2s8wu_ustat']) || !isset($_SESSION['d2s8wu_verified']) || !isset($_SESSION['d2s8wu_xdel']) || !isset($_SESSION['d2s8wu_ulevel'])) {
	echo json_encode(array('status' => 'error', 'message' => 'Unauthorized'));
	exit;
}

require_once dirname(__DIR__, 2) . '/lib/cnn.php';

$permitNo = isset($_GET['permit_no']) ? trim((string) $_GET['permit_no']) : '';
$excludeId = isset($_GET['exclude_id']) ? (int) $_GET['exclude_id'] : 0;

if ($permitNo === '') {
	echo json_encode(array('status' => 'ok', 'exists' => false));
	exit;
}

try {
	$db = new myDatabase();
	$conn = $db->getConnection();

	$sql = "SELECT permit_autoid FROM ore_transport_permit_tbl WHERE permit_no = :permit_no AND xdel = 0";
	if ($excludeId > 0) {
		$sql .= " AND permit_autoid <> :exclude_id";
	}
	$sql .= " LIMIT 1";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':permit_no', $permitNo);
	if ($excludeId > 0) {
		$stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
	}
	$stmt->execute();
	$exists = $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;

	echo json_encode(array('status' => 'ok', 'exists' => $exists));
} catch (Exception $e) {
	echo json_encode(array('status' => 'error', 'message' => 'Unable to check permit number'));
}
?>