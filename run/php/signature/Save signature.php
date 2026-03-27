<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Only POST allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);

if (!$body || empty($body['imageData'])) {
    echo json_encode(['success' => false, 'error' => 'No image data received']);
    exit;
}

$imageData = $body['imageData'];
$filename  = isset($body['filename']) ? basename($body['filename']) : 'signature_' . time() . '.png';

// Force .png extension
if (pathinfo($filename, PATHINFO_EXTENSION) !== 'png') {
    $filename = pathinfo($filename, PATHINFO_FILENAME) . '.png';
}

// Save folder
$uploadDir = __DIR__ . '/uploads/signatures/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Strip base64 header and decode
$base64Data = preg_replace('/^data:image\/png;base64,/', '', $imageData);
$decoded    = base64_decode($base64Data);

if ($decoded === false) {
    echo json_encode(['success' => false, 'error' => 'Invalid base64 data']);
    exit;
}

$filePath = $uploadDir . $filename;

if (file_put_contents($filePath, $decoded) !== false) {
    echo json_encode([
        'success'  => true,
        'filename' => $filename,
        'path'     => $filePath,
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to write file. Check folder permissions.']);
}
?>