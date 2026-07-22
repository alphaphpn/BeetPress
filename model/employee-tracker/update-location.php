<?php

if (file_exists("../../lib/cnn.php")) {
    require_once "../../lib/cnn.php";
} elseif (file_exists("../../../lib/cnn.php")) {
    require_once "../../../lib/cnn.php";
}

header('Content-Type: application/json');

$empid    = trim($_POST['empid']     ?? '');
$landmark = trim($_POST['landmark']  ?? '');
$lat      = trim($_POST['latitude']  ?? '');
$lng      = trim($_POST['longitude'] ?? '');
$meter    = trim($_POST['meter']     ?? '');

if (!$empid) {
    echo json_encode(['status' => 'error', 'msg' => 'Invalid request']);
    exit;
}

try {
    $db   = new myDatabase();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("UPDATE employee_tbl SET
        office_landmark=:landmark,
        office_latitude=:lat,
        office_longitude=:lng,
        office_meter=:meter
        WHERE emp_idcode=:empid");

    $stmt->execute([
        ':landmark' => $landmark,
        ':lat'      => $lat  !== '' ? $lat  : null,
        ':lng'      => $lng  !== '' ? $lng  : null,
        ':meter'    => $meter !== '' ? $meter : null,
        ':empid'    => $empid,
    ]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
}
