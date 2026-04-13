<?php
@ini_set('display_errors', 0);
error_reporting(0);
/**
 * Receives a JPEG frame from the host browser and saves it to disk.
 * Called by the host machine's browser every ~100ms for each broadcasting USB camera.
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'msg' => 'POST only']);
    exit;
}

$camId = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_POST['camId'] ?? '');
if (!$camId) {
    echo json_encode(['ok' => false, 'msg' => 'Missing camId']);
    exit;
}

if (empty($_FILES['frame']) || $_FILES['frame']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['ok' => false, 'msg' => 'No frame received']);
    exit;
}

// Validate it's actually a JPEG
$mime = mime_content_type($_FILES['frame']['tmp_name']);
if (!in_array($mime, ['image/jpeg', 'image/jpg', 'image/png'])) {
    echo json_encode(['ok' => false, 'msg' => 'Invalid file type']);
    exit;
}

$dir  = __DIR__ . '/frames/';
$file = $dir . $camId . '.jpg';

if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

if (move_uploaded_file($_FILES['frame']['tmp_name'], $file)) {
    // Write a small metadata file (last-seen timestamp)
    file_put_contents($dir . $camId . '.meta', json_encode([
        'camId'    => $camId,
        'updated'  => time(),
        'mime'     => $mime,
    ]));
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Failed to save frame']);
}
