<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (file_exists("../../lib/cnn.php")) {
    require_once "../../lib/cnn.php";
} elseif (file_exists("../../../lib/cnn.php")) {
    require_once "../../../lib/cnn.php";
}

header('Content-Type: application/json');

if (!in_array((int) ($_SESSION['d2s8wu_ulevel'] ?? 0), [1, 2], true)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'msg' => 'You are not authorized to edit employee tracker records.']);
    exit;
}

$empid       = trim($_POST['empid']        ?? '');
$officetitle = trim($_POST['officetitle']  ?? '');
$desig       = trim($_POST['desig']        ?? '');
$role        = trim($_POST['role']         ?? '0');
$wloc        = trim($_POST['wloc']         ?? '0');
$landmark    = trim($_POST['landmark']     ?? '');
$lat         = trim($_POST['lat']          ?? '');
$lng         = trim($_POST['lng']          ?? '');
$meter       = trim($_POST['meter']        ?? '');

if (!$empid) {
    echo json_encode(['status' => 'error', 'msg' => 'Invalid request']);
    exit;
}

try {
    $db   = new myDatabase();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("UPDATE employee_tbl SET
        officetitle       = :officetitle,
        designationforid  = :desig,
        employee_role     = :role,
        work_location     = :wloc,
        office_landmark   = :landmark,
        office_latitude   = :lat,
        office_longitude  = :lng,
        office_meter      = :meter
        WHERE emp_idcode  = :empid");

    $stmt->execute([
        ':officetitle'  => $officetitle,
        ':desig'        => $desig,
        ':role'         => (int)$role,
        ':wloc'         => (int)$wloc,
        ':landmark'     => $landmark,
        ':lat'          => $lat   !== '' ? $lat   : null,
        ':lng'          => $lng   !== '' ? $lng   : null,
        ':meter'        => $meter !== '' ? $meter : null,
        ':empid'        => $empid,
    ]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
}
