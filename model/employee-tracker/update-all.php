<?php

if (file_exists("../../lib/cnn.php")) {
    require_once "../../lib/cnn.php";
} elseif (file_exists("../../../lib/cnn.php")) {
    require_once "../../../lib/cnn.php";
}

header('Content-Type: application/json');

$empid       = trim($_POST['empid']        ?? '');
$officetitle = trim($_POST['officetitle']  ?? '');
$desig       = trim($_POST['desig']        ?? '');
$role        = trim($_POST['role']         ?? '0');
$wloc        = trim($_POST['wloc']         ?? '0');
$landmark    = trim($_POST['landmark']     ?? '');
$lat         = trim($_POST['lat']          ?? '');
$lng         = trim($_POST['lng']          ?? '');
$meter       = trim($_POST['meter']        ?? '');
$duty_status = intval($_POST['duty_status'] ?? 0);

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
        office_meter      = :meter,
        duty_status       = :duty_status
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
        ':duty_status'  => $duty_status,
        ':empid'        => $empid,
    ]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
}
