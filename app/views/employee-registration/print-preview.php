<?php

require_once dirname(__DIR__, 3) . '/lib/core.php';

if (
    !isset($_SESSION['d2s8wu_ustat'], $_SESSION['d2s8wu_verified'], $_SESSION['d2s8wu_xdel']) ||
    (int) $_SESSION['d2s8wu_ustat'] !== 1 ||
    (int) $_SESSION['d2s8wu_verified'] !== 1 ||
    (int) $_SESSION['d2s8wu_xdel'] !== 0
) {
    http_response_code(403);
    exit('Access denied.');
}

$token = isset($_GET['token']) ? (string) $_GET['token'] : '';
$details = $_SESSION['employee_registration_previews'][$token] ?? null;

if (!is_array($details) || empty($details['created_at']) || (time() - (int) $details['created_at']) > 1800) {
    http_response_code(404);
    exit('This registration print preview is no longer available.');
}

function printPreviewValue($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$fields = array(
    'Employee Name' => $details['full_name'],
    'Employee ID' => $details['employee_id'],
    'Employee PIN' => $details['employee_pin'],
    'Username' => $details['username'],
    'Password' => $details['password'],
    'Email Address' => $details['email'],
    'Phone Number' => $details['phone'],
    'Designation' => $details['designation'],
    'Employee Type' => $details['employee_type'],
    'Office' => $details['office'],
    'Biometric Location' => $details['bio_location'],
    'Biometric Number' => $details['bio_number'],
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Registration Information</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; margin: 36px; }
        main { max-width: 760px; margin: auto; }
        h1 { font-size: 24px; margin-bottom: 4px; }
        p { margin-top: 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        th, td { border: 1px solid #555; padding: 10px; text-align: left; vertical-align: top; }
        th { width: 35%; background: #f1f1f1; }
        .notice { margin-top: 20px; font-size: 12px; color: #555; }
        .actions { margin: 24px 0; }
        @media print { .actions { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
    <main>
        <h1>Employee Registration Information</h1>
        <p>Keep this copy secure. It contains the employee's login password and PIN.</p>
        <div class="actions"><button type="button" onclick="window.print()">Print</button></div>
        <table>
            <?php foreach ($fields as $label => $value): ?>
                <tr><th><?php echo printPreviewValue($label); ?></th><td><?php echo printPreviewValue($value); ?></td></tr>
            <?php endforeach; ?>
        </table>
        <p class="notice">Generated on <?php echo printPreviewValue(date('F j, Y g:i A')); ?>.</p>
    </main>
</body>
</html>
