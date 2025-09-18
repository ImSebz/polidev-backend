<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'No data received']);
        exit;
    }

    $nombre = $data['nombre'] ?? '';
    $apellido = $data['apellido'] ?? '';
    $email = $data['email'] ?? '';
    $celular = $data['celular'] ?? '';
    $tipo_documento = $data['tipo_documento'] ?? '';
    $numero_documento = $data['numero_documento'] ?? '';
    $ciudad = $data['ciudad'] ?? '';
    $contacto_preferido = $data['contacto_preferido'] ?? '';

    $conn = new mysqli('mysql.hostinger.com', 'u659584588_politotem', '7VaA8n#yG', 'u659584588_politotem');
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    $stmt = $conn->prepare('INSERT INTO usuarios (nombre, apellido, email, celular, tipo_documento, numero_documento, ciudad, contacto_preferido) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssssss', $nombre, $apellido, $email, $celular, $tipo_documento, $numero_documento, $ciudad, $contacto_preferido);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to insert data']);
    }
    $stmt->close();
    $conn->close();
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
