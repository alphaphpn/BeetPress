<!DOCTYPE html>
<html>
<body>

<?php
/**
 * Determines AM/PM segments based on logical time windows.
 * 
 * @param array $logs Array of datetime strings.
 * @return array Categorized punch times.
 */
function getAttendanceSegments(array $logs) {
    $results = [
        'AM-In'  => null,
        'AM-Out' => null,
        'PM-In'  => null,
        'PM-Out' => null
    ];

    if (empty($logs)) return $results;

    // Convert and sort
    $timestamps = array_map('strtotime', $logs);
    sort($timestamps);

foreach ($timestamps as $time) {
    $hour = (int)date('H', $time);
    $timeStr = date('H:i:s', $time);
    $currentDate = date('Y-m-d', $time);

    // 1. AM-In: Earliest punch before 12:00 PM (00:00 to 11:59:59)
    if ($hour < 12 && is_null($results['AM-In'])) {
        $results['AM-In'] = $timeStr;
    }
    // 2. AM-Out: Any punch between 11:00 AM and 12:30 PM
    // We keep updating this so the latest one in this window is captured
    elseif ($hour >= 11 && $time < strtotime($currentDate . ' 12:30:00')) {
        $results['AM-Out'] = $timeStr;
    }
    // 3. PM-In: First punch after 12:30 PM but before 2:30 PM (14:30)
    elseif ($time >= strtotime($currentDate . ' 12:30:00') && $hour < 15 && is_null($results['PM-In'])) {
        $results['PM-In'] = $timeStr;
    }
    // 4. PM-Out: Latest punch from 12:01 PM to 11:59 PM
    // We update this continuously so the final value is the absolute latest punch of the day
    elseif ($time > strtotime($currentDate . ' 12:00:00')) {
        $results['PM-Out'] = $timeStr;
    }
}

    return $results;
}

// --- Testing with your specific case ---
$attendance_log = [
    "2026-04-29 07:55:12", 
    "2026-04-29 12:29:45",
    "2026-04-29 13:00:22",
    "2026-04-29 17:01:00"
];

// If you have exactly 4 logs, a simple positional assignment is often safer:
function getAttendanceByPosition(array $logs) {
    $timestamps = array_map('strtotime', $logs);
    sort($timestamps);
    
    return [
        'AM-In'  => isset($timestamps[0]) ? date('H:i:s', $timestamps[0]) : null,
        'AM-Out' => isset($timestamps[1]) ? date('H:i:s', $timestamps[1]) : null,
        'PM-In'  => isset($timestamps[2]) ? date('H:i:s', $timestamps[2]) : null,
        'PM-Out' => isset($timestamps[3]) ? date('H:i:s', $timestamps[3]) : null,
    ];
}

$result = getAttendanceByPosition($attendance_log);

echo "<pre>";
print_r($result);
echo "</pre>";
?> 

</body>
</html>