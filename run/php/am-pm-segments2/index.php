<!DOCTYPE html>
<html>
<body>

<?php
function getAttendanceByDay(array $logs) {
    $daily_data = [];

    // 1. Group logs by Date
    foreach ($logs as $log) {
        $date = date('Y-m-d', strtotime($log));
        $daily_data[$date][] = $log;
    }

    $final_report = [];

    // 2. Process each day individually
    foreach ($daily_data as $date => $day_logs) {
        // Sort logs for this specific day chronologically
        sort($day_logs);
        
        // Using positional logic (In, Out, In, Out) 
        // as it is the most reliable for 4-punch days
        $final_report[$date] = [
            'AM-In'  => isset($day_logs[0]) ? date('H:i:s', strtotime($day_logs[0])) : null,
            'AM-Out' => isset($day_logs[1]) ? date('H:i:s', strtotime($day_logs[1])) : null,
            'PM-In'  => isset($day_logs[2]) ? date('H:i:s', strtotime($day_logs[2])) : null,
            'PM-Out' => isset($day_logs[3]) ? date('H:i:s', strtotime($day_logs[3])) : null,
        ];
    }

    return $final_report;
}

// --- Example Usage ---

$attendance_log = [
    "2026-04-29 07:55:12", 
    "2026-04-29 08:02:12", 
    "2026-04-29 12:01:45",
    "2026-04-29 12:29:45",
    "2026-04-29 13:00:22",
    "2026-04-29 17:01:00"
];

$report = getAttendanceByDay($attendance_log);

echo "<pre>";
print_r($report);
echo "</pre>";
?> 

</body>
</html>