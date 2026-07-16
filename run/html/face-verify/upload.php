<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video'])) {
    $targetDir = "uploads/";
    $fileName = "verification_" . date("Ymd_His") . ".webm";
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['video']['tmp_name'], $targetFile)) {
        echo json_encode([
            "status" => "success", 
            "message" => "Video saved", 
            "file" => $fileName
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Upload failed"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>