<?php
// model/employee/update-profile-image.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Define target directory (adjust paths if needed for your server structure)
// This jumps out of model/employee/ and looks for public/employeeID/
$targetDir = dirname(__DIR__, 2) . '/public/employeeID/';

// Ensure directory exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

$employeeId = isset($_POST['employee_id']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['employee_id']) : '';

if (empty($employeeId)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing employee ID']);
    exit;
}

if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded or upload error']);
    exit;
}

$fileTmpPath = $_FILES['profile_image']['tmp_name'];
$targetFilePath = $targetDir . $employeeId . '.jpeg';

// Move uploaded file to destination, overwriting the old one as a jpeg
if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
    
    // We return the relative path that the Javascript expects to use for display
    // e.g. "public/employeeID/10000000.jpeg"
    // The Javascript will prepend the base URL automatically.
    echo json_encode([
        'status' => 'success', 
        'path' => 'public/employeeID/' . $employeeId . '.jpeg'
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save the uploaded image to the server']);
}
?>