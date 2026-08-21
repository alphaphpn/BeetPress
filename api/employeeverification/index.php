<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once '../../lib/env.php';

function verificationResponse($status, $payload)
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

$input = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
$employeeNumber = trim((string) ($input['employee_number'] ?? ''));
// QR codes may append metadata after the employee number, e.g. 87654321-20260308011035.
// The first eight digits are the employee ID used by employee_tbl.
if ($employeeNumber !== '') {
    if (preg_match('/^(\d{8})/', $employeeNumber, $matches)) {
        $employeeNumber = $matches[1];
    } else {
        verificationResponse(422, [
            'status' => 'error',
            'message' => 'Employee number must start with an 8-digit ID.'
        ]);
    }
}
$firstName = trim((string) ($input['first_name'] ?? ''));
$lastName = trim((string) ($input['last_name'] ?? ''));
$gender = trim((string) ($input['gender'] ?? ''));
$birthday = trim((string) ($input['birthday'] ?? ''));

if ($employeeNumber === '' && ($firstName === '' || $lastName === '' || $gender === '' || $birthday === '')) {
    verificationResponse(422, [
        'status' => 'error',
        'message' => 'Enter an employee number, or provide first name, last name, gender, and birthday.'
    ]);
}

try {
    $connection = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $uname, $pw, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    if ($employeeNumber !== '') {
        $statement = $connection->prepare(
            'SELECT emp_idcode, emp_name_forid, gender, birthday, officename_forid, designationforid
             FROM employee_tbl
             WHERE emp_idcode = :employee_number AND xdel = 0
             LIMIT 1'
        );
        $statement->execute([':employee_number' => $employeeNumber]);
    } else {
        $statement = $connection->prepare(
            'SELECT emp_idcode, emp_name_forid, gender, birthday, officename_forid, designationforid
             FROM employee_tbl
             WHERE xdel = 0
               AND LOWER(emp_name_forid) LIKE LOWER(:first_name)
               AND LOWER(emp_name_forid) LIKE LOWER(:last_name)
               AND LOWER(TRIM(gender)) = LOWER(TRIM(:gender))
               AND birthday = :birthday
             ORDER BY emp_idcode ASC
             LIMIT 1'
        );
        $statement->execute([
            ':first_name' => '%' . $firstName . '%',
            ':last_name' => '%' . $lastName . '%',
            ':gender' => $gender,
            ':birthday' => $birthday,
        ]);
    }

    $employee = $statement->fetch();
    if (!$employee) {
        verificationResponse(404, [
            'status' => 'not_found',
            'message' => 'No active employee record matched the information provided.'
        ]);
    }

    $employee['profile_image'] = rtrim($pixloc, '/') . '/public/employeeID/' . rawurlencode($employee['emp_idcode']) . '.jpeg';
    verificationResponse(200, ['status' => 'success', 'employee' => $employee]);
} catch (PDOException $exception) {
    error_log('Employee verification lookup failed: ' . $exception->getMessage());
    verificationResponse(500, [
        'status' => 'error',
        'message' => 'The employee verification service is temporarily unavailable.'
    ]);
}
