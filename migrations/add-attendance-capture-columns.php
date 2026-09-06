<?php
// Run once per installation; safe to rerun without changing existing data.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/../lib/env.php';
$connection = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $uname, $pw, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$columns = $connection->query('SHOW COLUMNS FROM employee_dtr_sub_tbl')->fetchAll(PDO::FETCH_UNIQUE | PDO::FETCH_ASSOC);
$additions = [];
foreach (['capture_am_in', 'capture_am_out', 'capture_pm_in', 'capture_pm_out'] as $column) {
    if (!isset($columns[$column])) $additions[] = "ADD COLUMN `$column` TEXT NULL";
}
if ($additions) $connection->exec('ALTER TABLE employee_dtr_sub_tbl ' . implode(', ', $additions));
$columns = $connection->query('SHOW COLUMNS FROM employee_dtr_sub_tbl')->fetchAll(PDO::FETCH_UNIQUE | PDO::FETCH_ASSOC);
foreach (['capture_am_in', 'capture_am_out', 'capture_pm_in', 'capture_pm_out'] as $column) {
    if (strtolower($columns[$column]['Type'] ?? '') !== 'text') throw new RuntimeException("Expected TEXT column: $column");
}
echo "Verified all four attendance capture columns are TEXT.\n";
