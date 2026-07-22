<?php

if (file_exists("../../lib/cnn.php")) {
    require_once "../../lib/cnn.php";
} elseif (file_exists("../../../lib/cnn.php")) {
    require_once "../../../lib/cnn.php";
}

header('Content-Type: application/json');

$empid = trim($_POST['empid'] ?? '');
$field = trim($_POST['field'] ?? '');
$value = trim($_POST['value'] ?? '');

$allowed = ['officetitle', 'designationforid', 'employee_role', 'work_location'];

if (!$empid || !in_array($field, $allowed)) {
    echo json_encode(['status' => 'error', 'msg' => 'Invalid request']);
    exit;
}

try {
    $db   = new myDatabase();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("UPDATE employee_tbl SET {$field}=:val WHERE emp_idcode=:empid");
    $stmt->execute([':val' => $value, ':empid' => $empid]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
}
