<?php
// Browser attendance flow. Legacy integrations continue to use index.php's GET API.
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');
session_start();
date_default_timezone_set('Asia/Manila');
require __DIR__ . '/../../lib/env.php';
require_once __DIR__ . '/../../lib/attendance-geofence.php';
function kioskReply($code, $data) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}
$capturePath = null;
function kioskExistingTimeIn($cnn, $id, $action, $date) {
    if (!in_array($action, ['amtimein', 'pmtimein'], true)) return null;
    $timeOut = $action === 'amtimein' ? 'amtimeout' : 'pmtimeout';
    $period = $action === 'amtimein' ? 'AM' : 'PM';
    $outStmt = $cnn->prepare("SELECT $timeOut FROM employee_dtr_sub_tbl WHERE emp_idcode = ? AND yearno = ? AND monthno = ? AND dayno = ? AND TRIM(COALESCE($timeOut, '')) <> '' LIMIT 1");
    $outStmt->execute([$id, $date->format('Y'), (int)$date->format('m'), (int)$date->format('d')]);
    $outValue = $outStmt->fetchColumn();
    if ($outValue !== false) return 'You cannot time in for ' . $period . ' because you have already timed out at ' . $outValue . '. Your attendance has not been changed.';
    $stmt = $cnn->prepare("SELECT $action FROM employee_dtr_sub_tbl WHERE emp_idcode = ? AND yearno = ? AND monthno = ? AND dayno = ? AND TRIM(COALESCE($action, '')) <> '' LIMIT 1");
    $stmt->execute([$id, $date->format('Y'), (int)$date->format('m'), (int)$date->format('d')]);
    $value = $stmt->fetchColumn();
    return $value === false ? null : 'You have already timed in for ' . ($action === 'amtimein' ? 'AM' : 'PM') . ' at ' . $value . '. Your existing time has not been changed.';
}
$bufferLevel = ob_get_level();
try {
    if (!isset($_POST['csrf']) || !is_string($_POST['csrf']) ||
        !hash_equals($_SESSION['attendance_csrf'] ?? '', $_POST['csrf']) || empty($_SESSION['attendance_csrf'])) {
        kioskReply(403, ['status' => 'error', 'message' => 'Reload the attendance page and try again.']);
    }
    $action = $_POST['action'] ?? '';
    $cnn = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $uname, $pw, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    if ($action === 'verify') {
        unset($_SESSION['attendance_delete']);
        unset($_SESSION['attendance_employee']);
        $id = $_POST['id'] ?? '';
        $pin = $_POST['pin'] ?? '';
        if (!is_string($id) || !is_string($pin) || !preg_match('/^[0-9]{1,32}$/D', $id) || !preg_match('/^[0-9]{1,64}$/D', $pin)) {
            kioskReply(400, ['status' => 'error', 'message' => 'Employee ID and PIN must contain numbers only.']);
        }
        if (time() < ($_SESSION['attendance_retry'] ?? 0)) {
            kioskReply(429, ['status' => 'error', 'message' => 'Please wait a few seconds before trying again.']);
        }
        $stmt = $cnn->prepare('SELECT * FROM employee_tbl WHERE emp_idcode = ? LIMIT 1');
        $stmt->execute([$id]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$employee || !hash_equals((string)$employee['pinword'], md5($pin))) {
            $_SESSION['attendance_retry'] = time() + 3;
            kioskReply(401, ['status' => 'error', 'message' => 'Employee ID or PIN is incorrect.']);
        }
        $today = new DateTimeImmutable();
        $duplicate = kioskExistingTimeIn($cnn, $id, $_POST['attendance_action'] ?? '', $today);
        $logsStmt = $cnn->prepare('SELECT amtimein, amtimeout, pmtimein, pmtimeout FROM employee_dtr_sub_tbl WHERE emp_idcode = ? AND yearno = ? AND monthno = ? AND dayno = ? LIMIT 1');
        $logsStmt->execute([$id, $today->format('Y'), (int)$today->format('m'), (int)$today->format('d')]);
        $logs = $logsStmt->fetch(PDO::FETCH_ASSOC) ?: ['amtimein' => '', 'amtimeout' => '', 'pmtimein' => '', 'pmtimeout' => ''];
        $logs['date'] = $today->format('F j, Y');
        $_SESSION['attendance_delete'] = ['id' => $id, 'year' => $today->format('Y'), 'month' => $today->format('m'), 'day' => $today->format('d'), 'expires' => time() + 120, 'token' => bin2hex(random_bytes(32))];
        $_SESSION['attendance_employee'] = ['id' => $id, 'expires' => time() + 120];
        $photo = null;
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
            if (is_file(__DIR__ . "/../../public/employeeID/{$id}.{$extension}")) {
                $photo = $domainhome . "/public/employeeID/{$id}.{$extension}";
                break;
            }
        }
        kioskReply(200, ['status' => 'success', 'attendance' => $logs, 'duplicate_message' => $duplicate,
            'delete_token' => $_SESSION['attendance_delete']['token'], 'employee' => [
            'name' => $employee['emp_name'], 'office' => $employee['officetitle'], 'photo' => $photo,
            'landmark' => $employee['office_landmark'] ?? '',
            'geofence' => ['latitude' => $employee['office_langitude'] ?? $employee['office_latitude'] ?? null,
                'longitude' => $employee['office_longitude'] ?? null,
                'radius' => $employee['office_meter'] ?? null, 'workLocation' => $employee['work_location'] ?? null]
        ]]);
    }
    if ($action === 'delete_time') {
        $context = $_SESSION['attendance_delete'] ?? null;
        $field = $_POST['field'] ?? '';
        $token = $_POST['delete_token'] ?? '';
        if (!$context || $context['expires'] < time() || !is_string($token) || !hash_equals($context['token'], $token)) {
            kioskReply(403, ['status' => 'error', 'message' => 'This attendance session has expired. Please verify again.']);
        }
        if (!in_array($field, ['amtimein', 'amtimeout', 'pmtimein', 'pmtimeout'], true)) {
            kioskReply(400, ['status' => 'error', 'message' => 'Invalid attendance time.']);
        }
        $lock = $cnn->prepare('SELECT GET_LOCK(?, 10)');
        $lock->execute(['attendance:' . $context['id']]);
        if ((int)$lock->fetchColumn() !== 1) throw new RuntimeException('Attendance is busy.');
        $cnn->beginTransaction();
        $params = [$context['id'], $context['year'], $context['month'], $context['day']];
        $deleteStmt = $cnn->prepare("UPDATE employee_dtr_sub_tbl SET $field = '' WHERE emp_idcode = ? AND yearno = ? AND monthno = ? AND dayno = ?");
        $deleteStmt->execute($params);
        $readStmt = $cnn->prepare('SELECT amtimein, amtimeout, pmtimein, pmtimeout FROM employee_dtr_sub_tbl WHERE emp_idcode = ? AND yearno = ? AND monthno = ? AND dayno = ? LIMIT 1');
        $readStmt->execute($params);
        $attendance = $readStmt->fetch(PDO::FETCH_ASSOC);
        if (!$attendance) throw new RuntimeException('Attendance record was not found.');
        $cnn->commit();
        kioskReply(200, ['status' => 'success', 'message' => 'Attendance time deleted.', 'attendance' => $attendance]);
    }
    $verified = $_SESSION['attendance_employee'] ?? null;
    if (!$verified || $verified['expires'] < time()) {
        kioskReply(401, ['status' => 'error', 'message' => 'Please verify your Employee ID and PIN again.']);
    }
    $now = new DateTimeImmutable();
    $allowed = (int)$now->format('H') < 12 ? ['amtimein', 'amtimeout'] : ['pmtimein', 'pmtimeout'];
    if (!in_array($action, $allowed, true)) {
        kioskReply(400, ['status' => 'error', 'message' => 'Choose the attendance button for the current AM/PM period.']);
    }
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    if (!is_string($latitude) || !is_string($longitude) || !is_numeric($latitude) || !is_numeric($longitude) ||
        !is_finite((float)$latitude) || !is_finite((float)$longitude) ||
        abs((float)$latitude) > 90 || abs((float)$longitude) > 180) {
        kioskReply(400, ['status' => 'error', 'message' => 'A valid GPS location is required. Allow location access and try again.']);
    }
    $gpsLocation = number_format((float)$latitude, 7, '.', '') . ',' . number_format((float)$longitude, 7, '.', '');
    // Read current settings for the authenticated employee, never from the browser.
    $geofenceStmt = $cnn->prepare('SELECT * FROM employee_tbl WHERE emp_idcode = ? LIMIT 1');
    $geofenceStmt->execute([$verified['id']]);
    $geofenceEmployee = $geofenceStmt->fetch(PDO::FETCH_ASSOC);
    if (!$geofenceEmployee) kioskReply(401, ['status' => 'error', 'message' => 'Employee information was not found. Please verify again.']);
    $geofenceError = attendanceGeofenceError($geofenceEmployee, (float)$latitude, (float)$longitude);
    if ($geofenceError !== null) kioskReply(422, ['status' => 'error', 'message' => $geofenceError]);
    $gpsParameters = ['amtimein' => 'gpslocamin', 'amtimeout' => 'gpslocamout', 'pmtimein' => 'gpslocpmin', 'pmtimeout' => 'gpslocpmout'];
    $upload = $_FILES['capture'] ?? null;
    if (!$upload || $upload['error'] !== UPLOAD_ERR_OK || $upload['size'] > 2097152 || !is_uploaded_file($upload['tmp_name'])) {
        kioskReply(400, ['status' => 'error', 'message' => 'A camera capture is required (maximum 2 MB).']);
    }
    $info = @getimagesize($upload['tmp_name']);
    if (!$info || $info[2] !== IMAGETYPE_JPEG || $info[0] > 1920 || $info[1] > 1920) {
        kioskReply(400, ['status' => 'error', 'message' => 'Invalid camera image. Please try again.']);
    }
    $id = $verified['id'];
    // Serialize monthly generation and updates from attendance kiosks.
    $lock = $cnn->prepare('SELECT GET_LOCK(?, 10)');
    $lock->execute(['attendance:' . $id]);
    if ((int)$lock->fetchColumn() !== 1) throw new RuntimeException('Attendance is busy. Please try again.');
    $cnn->beginTransaction();
    $duplicate = kioskExistingTimeIn($cnn, $id, $action, $now);
    if ($duplicate !== null) {
        $cnn->rollBack();
        kioskReply(409, ['status' => 'error', 'message' => $duplicate]);
    }
    $relativeFolder = 'public/employee_attendance_capture/' . $id . '/' . $now->format('Y/m/d');
    $folder = __DIR__ . '/../../' . $relativeFolder;
    if (!is_dir($folder) && !mkdir($folder, 0755, true) && !is_dir($folder)) throw new RuntimeException('Could not create the capture folder.');
    $filename = $id . '-' . $now->format('Y-m-d-His') . '.jpg';
    $relativeCapturePath = $relativeFolder . '/' . $filename;
    $path = $folder . '/' . $filename;
    $file = @fopen($path, 'x+b');
    if (!$file) throw new RuntimeException('A capture already exists for this second. Please try again.');
    $capturePath = $path;
    $bytes = file_get_contents($upload['tmp_name']);
    $written = fwrite($file, $bytes);
    fclose($file);
    if ($written !== strlen($bytes)) throw new RuntimeException('Could not save the camera capture.');
    // Run the existing DTR save/update implementation within this transaction.
    define('ATTENDANCE_KIOSK', true);
    $_GET = ['id' => $id, 'year' => $now->format('Y'), 'month' => $now->format('m'), 'day' => $now->format('d'),
        'token' => '0a7a004339f450a46fe7b34767c54577', $action => $now->format('g:i')];
    $_GET[$gpsParameters[$action]] = $gpsLocation;
    ob_start();
    include __DIR__ . '/index.php';
    $result = json_decode(ob_get_clean(), true);
    header_remove('Access-Control-Allow-Origin');
    if (($result['status'] ?? '') !== 'success') throw new RuntimeException('Attendance could not be saved. Please try again.');
    // Store only this action's image path in the same transaction as its time and GPS.
    $captureColumns = ['amtimein' => 'capture_am_in', 'amtimeout' => 'capture_am_out', 'pmtimein' => 'capture_pm_in', 'pmtimeout' => 'capture_pm_out'];
    $captureStmt = $cnn->prepare('UPDATE employee_dtr_sub_tbl SET ' . $captureColumns[$action] . ' = ? WHERE emp_idcode = ? AND yearno = ? AND monthno = ? AND dayno = ?');
    $captureStmt->execute([$relativeCapturePath, $id, (int)$now->format('Y'), (int)$now->format('m'), (int)$now->format('d')]);
    $attendanceStmt = $cnn->prepare('SELECT amtimein, amtimeout, pmtimein, pmtimeout FROM employee_dtr_sub_tbl WHERE emp_idcode = ? AND yearno = ? AND monthno = ? AND dayno = ? LIMIT 1');
    $attendanceStmt->execute([$id, $now->format('Y'), (int)$now->format('m'), (int)$now->format('d')]);
    $attendance = $attendanceStmt->fetch(PDO::FETCH_ASSOC);
    if (!$attendance) throw new RuntimeException('Saved attendance could not be retrieved.');
    $attendance['date'] = $now->format('F j, Y');
    $cnn->commit();
    unset($_SESSION['attendance_employee']);
    $_SESSION['attendance_delete'] = ['id' => $id, 'year' => $now->format('Y'), 'month' => $now->format('m'), 'day' => $now->format('d'), 'expires' => time() + 120, 'token' => bin2hex(random_bytes(32))];
    kioskReply(200, ['status' => 'success', 'message' => 'Attendance and camera capture saved at ' . $now->format('h:i:s A') . '.', 'attendance' => $attendance, 'delete_token' => $_SESSION['attendance_delete']['token']]);
} catch (Throwable $e) {
    while (ob_get_level() > $bufferLevel) ob_end_clean();
    if (isset($cnn) && $cnn->inTransaction()) $cnn->rollBack();
    if ($capturePath && is_file($capturePath)) unlink($capturePath);
    error_log('Attendance kiosk: ' . $e->getMessage());
    kioskReply(500, ['status' => 'error', 'message' => 'Attendance could not be saved. Please try again or contact your administrator.']);
}
