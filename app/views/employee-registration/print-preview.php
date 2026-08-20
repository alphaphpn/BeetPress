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

$sections = $details['sections'] ?? array(
    'Employee Information' => array(
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
    ),
);

$photo = isset($details['photo']) && is_string($details['photo']) && strpos($details['photo'], 'data:image/') === 0
    ? $details['photo']
    : null;

$fieldLayouts = array(
    'Registration Details' => array(
        array('Registered as Voter', 'Postal Code'),
    ),
    'Personal Information' => array(
        array('Nickname / Alias', 'Title'),
        'First Name',
        'Middle Name',
        'Last Name',
        array('Suffix', 'Gender'),
        'Profession',
        array('Date of Birth', 'Place of Birth'),
    ),
    'Contact Information' => array('Primary Phone', 'Secondary Phone', 'Email Address'),
    'Account Information' => array(
        array('Username', 'Password'),
    ),
    'Employee Information' => array(
        array('Employee ID', 'Employee PIN'),
        array('Employee Status', 'Designation'),
        array('values' => array('Office Title', 'Head Officer')),
    ),
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
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #555; padding: 10px; text-align: left; vertical-align: top; }
        th { background: #f1f1f1; }
        .single-label { width: 35%; }
        .pair-label { width: 18%; }
        h2 { font-size: 17px; margin: 28px 0 8px; color: #1f4e79; }
        .photo { display: block; width: 150px; max-height: 190px; object-fit: cover; border: 1px solid #555; margin: 16px 0; }
        .notice { margin-top: 20px; font-size: 12px; color: #555; }
        .actions { margin: 24px 0; }
        .print-settings { font-size: 13px; color: #555; margin: 0 0 16px; }
        @page { size: 8.5in 13in; margin: 0.25in; }
        @media print {
            .actions, .print-settings { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <main>
        <h1>Employee Registration Information</h1>
        <p><strong>If any information below is incorrect, please advise us to update your information immediately.</strong></p>
        <p>Keep this copy secure. It contains the employee's login password and PIN.</p>
        <p class="print-settings">Print settings: Paper size 8.5 × 13 in (Legal), Margins Minimum, Scale Custom 95%.</p>
        <div class="actions"><button type="button" onclick="window.print()">Print</button></div>
        <?php if ($photo !== null): ?>
            <img class="photo" src="<?php echo printPreviewValue($photo); ?>" alt="Employee profile picture">
        <?php endif; ?>
        <?php foreach ($sections as $sectionTitle => $fields): ?>
            <h2><?php echo printPreviewValue($sectionTitle); ?></h2>
            <table>
                <?php foreach ($fieldLayouts[$sectionTitle] ?? array_keys($fields) as $fieldLayout): ?>
                    <?php if (is_array($fieldLayout) && isset($fieldLayout['values'])): ?>
                        <tr>
                            <?php foreach ($fieldLayout['values'] as $label): ?>
                                <td colspan="<?php echo count($fieldLayout['values']) === 1 ? '4' : '2'; ?>"><?php echo printPreviewValue(trim((string) ($fields[$label] ?? '')) !== '' ? $fields[$label] : '-'); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php elseif (is_array($fieldLayout)): ?>
                        <tr>
                            <?php foreach ($fieldLayout as $label): ?>
                                <th class="pair-label"><?php echo printPreviewValue($label); ?></th>
                                <td><?php echo printPreviewValue(trim((string) ($fields[$label] ?? '')) !== '' ? $fields[$label] : '-'); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php else: ?>
                        <tr><th class="single-label"><?php echo printPreviewValue($fieldLayout); ?></th><td colspan="3"><?php echo printPreviewValue(trim((string) ($fields[$fieldLayout] ?? '')) !== '' ? $fields[$fieldLayout] : '-'); ?></td></tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>
        <p class="notice">Generated on <?php echo printPreviewValue(date('F j, Y g:i A')); ?>.</p>
    </main>
</body>
</html>
