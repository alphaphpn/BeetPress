<?php
// Get the JSON data from the request body
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['image'])) {
    $img = $data['image'];
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    
    // Decode the base64 string
    $fileData = base64_decode($img);

    // Ensure the folder exists
    $folder = 'captured_faces/';
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    // Save with a unique filename
    $fileName = $folder . 'face_' . time() . '_' . uniqid() . '.jpg';
    file_put_contents($fileName, $fileData);
    
    echo "Face saved: " . $fileName;
}
?>