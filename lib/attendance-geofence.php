<?php
// Returns a user-facing rejection, or null when attendance is allowed.
function attendanceGeofenceError(array $employee, float $latitude, float $longitude): ?string {
    $workLocation = (string)($employee['work_location'] ?? '');
    if ($workLocation === '0') return null;
    if ($workLocation !== '1') return 'Your work location is not configured. Please contact your administrator.';

    // Older installations use office_latitude for the latitude column.
    $centerLatitude = $employee['office_langitude'] ?? $employee['office_latitude'] ?? null;
    $centerLongitude = $employee['office_longitude'] ?? null;
    $radius = $employee['office_meter'] ?? null;
    foreach ([$centerLatitude, $centerLongitude, $radius] as $value) {
        if (!is_numeric($value) || !is_finite((float)$value)) {
            return 'Your office geofence is not configured correctly. Please contact your administrator.';
        }
    }
    if (abs((float)$centerLatitude) > 90 || abs((float)$centerLongitude) > 180 || (float)$radius < 0) {
        return 'Your office geofence is not configured correctly. Please contact your administrator.';
    }
    $latitudeDelta = deg2rad($latitude - (float)$centerLatitude);
    $longitudeDelta = deg2rad($longitude - (float)$centerLongitude);
    $a = sin($latitudeDelta / 2) ** 2 + cos(deg2rad((float)$centerLatitude)) * cos(deg2rad($latitude)) * sin($longitudeDelta / 2) ** 2;
    $distance = 6371000 * 2 * asin(sqrt(max(0, min(1, $a))));
    if ($distance > (float)$radius) {
        return 'Attendance not saved: you are outside your office geofence (approximately ' . number_format($distance, 1) . ' meters from the center; allowed radius: ' . number_format((float)$radius, 1) . ' meters). Please move within the allowed area and try again.';
    }
    return null;
}
