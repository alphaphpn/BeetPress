<?php
// Isolated persistence regression: never connects to the application database.
require __DIR__ . '/../lib/env.php';
define('ATTENDANCE_KIOSK', true);
$_SERVER['REQUEST_METHOD'] = 'POST';
$cnn = new PDO('sqlite::memory:');
$cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$source = file_get_contents(__DIR__ . '/../api/employeesubdtr/index.php');
preg_match('/SELECT agency_code, agency_name, profileid, bio_location, bio_no,(.*?)FROM employee_tbl/s', $source, $match);
$employeeColumns = array_map('trim', explode(',', 'agency_code, agency_name, profileid, bio_location, bio_no,' . $match[1]));
$employeeColumns[] = 'emp_idcode';
$cnn->exec('CREATE TABLE employee_tbl (' . implode(' TEXT, ', $employeeColumns) . ' TEXT)');
foreach (['employee_dtr_tbl', 'employee_dtr_sub_tbl'] as $table) {
    preg_match('/INSERT INTO ' . $table . '\s*\((.*?)\)\s*VALUES/s', $source, $match);
    $columns = array_map('trim', explode(',', $match[1]));
    $cnn->exec('CREATE TABLE ' . $table . ' (' . implode(' TEXT, ', $columns) . ' TEXT)');
}
$values = array_fill(0, count($employeeColumns), 'fixture');
$values[count($values) - 1] = '00123';
$cnn->prepare('INSERT INTO employee_tbl VALUES (' . implode(',', array_fill(0, count($values), '?')) . ')')->execute($values);
$_GET = ['id' => '00123', 'year' => '2026', 'month' => '09', 'day' => '10', 'token' => '0a7a004339f450a46fe7b34767c54577', 'amtimein' => '8:01'];
function runSave() {
    global $cnn, $host, $db, $uname, $pw;
    ob_start();
    include __DIR__ . '/../api/employeesubdtr/index.php';
    $result = json_decode(ob_get_clean(), true);
    if (($result['status'] ?? '') !== 'success') throw new RuntimeException('Save failed');
}
function check($value, $message) { if (!$value) throw new RuntimeException($message); }
$cnn->beginTransaction();
$_GET['gpslocamin'] = '14.5995000,120.9842000';
runSave();
check($cnn->inTransaction(), 'Kiosk must own the transaction');
check((int)$cnn->query('SELECT COUNT(*) FROM employee_dtr_tbl')->fetchColumn() === 1, 'Monthly header missing');
check((int)$cnn->query('SELECT COUNT(*) FROM employee_dtr_sub_tbl')->fetchColumn() === 30, 'Month generation failed');
unset($_GET['amtimein'], $_GET['gpslocamin']);
$_GET['pmtimeout'] = '5:43';
runSave();
$row = $cnn->query("SELECT * FROM employee_dtr_sub_tbl WHERE dayno = '10'")->fetch(PDO::FETCH_ASSOC);
check($row['amtimein'] === '8:01' && $row['pmtimeout'] === '5:43', 'Update must preserve other attendance fields');
check((int)$cnn->query('SELECT COUNT(*) FROM employee_dtr_sub_tbl')->fetchColumn() === 30, 'Update duplicated records');
check($row['attendance_gps_location_am_in'] === '14.5995000,120.9842000', 'AM-In GPS must persist through other updates');
unset($_GET['pmtimeout']);
$expectedGps = ['attendance_gps_location_am_in' => '14.5995000,120.9842000'];
foreach ([['amtimeout', 'gpslocamout', 'attendance_gps_location_am_out', '11:59'], ['pmtimein', 'gpslocpmin', 'attendance_gps_location_pm_in', '1:01'], ['pmtimeout', 'gpslocpmout', 'attendance_gps_location_pm_out', '5:44']] as $i => $entry) {
    [$timeKey, $gpsKey, $column, $timeValue] = $entry;
    $gpsValue = '14.599500' . $i . ',120.984200' . $i;
    $_GET[$timeKey] = $timeValue;
    $_GET[$gpsKey] = $gpsValue;
    runSave();
    $expectedGps[$column] = $gpsValue;
    $row = $cnn->query("SELECT * FROM employee_dtr_sub_tbl WHERE dayno = '10'")->fetch(PDO::FETCH_ASSOC);
    check($row[$timeKey] === $timeValue, 'Attendance time must update with GPS');
    foreach ($expectedGps as $gpsColumn => $expectedValue) check($row[$gpsColumn] === $expectedValue, 'GPS update must preserve each attendance location');
    unset($_GET[$timeKey], $_GET[$gpsKey]);
}
$cnn->rollBack();
check((int)$cnn->query('SELECT COUNT(*) FROM employee_dtr_tbl')->fetchColumn() === 0, 'Rollback must undo all persistence');
echo "PASS: month generation, selective time and GPS updates for all four entries, and transaction rollback\n";
