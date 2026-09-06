<?php
require __DIR__ . '/../lib/attendance-geofence.php';
function checkFence($condition, $message) {
    if (!$condition) throw new RuntimeException($message);
}
$office = ['work_location' => '1', 'office_langitude' => '0', 'office_longitude' => '0', 'office_meter' => '100'];
checkFence(attendanceGeofenceError(['work_location' => '0'], 50, 120) === null, 'On-Field must be exempt without office settings');
checkFence(attendanceGeofenceError($office, 0, 0) === null, 'Center must be allowed');
checkFence(attendanceGeofenceError($office, 0.00089, 0) === null, 'Inside radius must be allowed');
checkFence(attendanceGeofenceError($office, 0.00091, 0) !== null, 'Outside radius must be rejected');
checkFence(attendanceGeofenceError($office, 0, 0.00091) !== null, 'Longitude distance must be checked');
checkFence(attendanceGeofenceError(array_replace($office, ['office_meter' => 'bad']), 0, 0) !== null, 'Invalid radius must be rejected');
checkFence(attendanceGeofenceError(array_replace($office, ['office_langitude' => '91']), 0, 0) !== null, 'Invalid center must be rejected');
checkFence(attendanceGeofenceError(['work_location' => '1'], 0, 0) !== null, 'Missing settings must be rejected');
checkFence(attendanceGeofenceError([], 0, 0) !== null, 'Unknown work location must not bypass geofence');
unset($office['office_langitude']);
$office['office_latitude'] = '0';
checkFence(attendanceGeofenceError($office, 0, 0) === null, 'Existing latitude column must be supported');
echo "PASS: On-Field exemption, inside/outside radius, invalid settings, and latitude column compatibility\n";
