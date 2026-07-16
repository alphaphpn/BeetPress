<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video'])) {
    $dir = 'uploads/';
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    // Create a unique filename
    $filename = 'auth_' . time() . '_' . uniqid() . '.webm';
    $target = $dir . $filename;

    if (move_uploaded_file($_FILES['video']['tmp_name'], $target)) {
        echo json_encode(["status" => "success", "file" => $filename]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Could not save file"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>