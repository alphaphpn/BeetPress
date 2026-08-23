<?php
header('Content-Type: application/json; charset=UTF-8');
require_once '../../lib/core.php';
require_once '../../lib/env.php';

if (empty($_SESSION['d2s8wu_ustat']) || empty($_SESSION['d2s8wu_verified'])) { http_response_code(401); echo json_encode(array('message' => 'Unauthorized')); exit; }
$employeeId = trim((string) ($_GET['employee_id'] ?? ''));
if (!preg_match('/^\d{8}$/', $employeeId)) { http_response_code(422); echo json_encode(array('message' => 'Enter an 8-digit employee ID.')); exit; }
try {
    $cnn = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $uname, $pw, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $stmt = $cnn->prepare('SELECT emp_idcode, emp_name_forid, officename_forid, officeid FROM employee_tbl WHERE emp_idcode = :id AND xdel = 0 LIMIT 1');
    $stmt->execute(array(':id' => $employeeId)); $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$employee) { http_response_code(404); echo json_encode(array('message' => 'Employee not found.')); exit; }
    echo json_encode(array('employee' => $employee));
} catch (PDOException $e) { http_response_code(500); echo json_encode(array('message' => 'Employee lookup is unavailable.')); }
