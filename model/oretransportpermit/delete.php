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

$permitId = isset($_POST['permit_autoid']) ? (int) $_POST['permit_autoid'] : 0;
if ($permitId <= 0) {
	echo json_encode(array('status' => 'error', 'message' => 'Invalid permit record.'));
	exit;
}

try {
	$db = new myDatabase();
	$conn = $db->getConnection();
	$user = trim((string) ($_SESSION['d2s8wu_uname'] ?? $_SESSION['d2s8wu_uid'] ?? 'System'));

	$stmt = $conn->prepare("UPDATE ore_transport_permit_tbl SET xdel = 1, modifiedby = :user WHERE permit_autoid = :id AND xdel = 0");
	$stmt->bindParam(':user', $user);
	$stmt->bindParam(':id', $permitId, PDO::PARAM_INT);
	$stmt->execute();

	if ($stmt->rowCount() > 0) {
		echo json_encode(array('status' => 'success', 'message' => 'Permit deleted successfully.'));
	} else {
		echo json_encode(array('status' => 'error', 'message' => 'Permit not found or already deleted.'));
	}
} catch (Exception $e) {
	echo json_encode(array('status' => 'error', 'message' => 'Unable to delete permit.'));
}
?>