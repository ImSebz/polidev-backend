<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$uploads_dir = __DIR__ . '/../uploads';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['file'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No file uploaded']);
        exit;
    }

    $file = $_FILES['file'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $base = pathinfo($file['name'], PATHINFO_FILENAME);
    $filename = $base . '_' . time() . '.' . $ext;
    $target = $uploads_dir . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $publicUrl = $protocol . '://' . $host . '/uploads/' . $filename;
        echo json_encode(['url' => $publicUrl]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to move uploaded file']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
