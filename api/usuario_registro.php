<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Cargar variables de entorno desde .env
if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
    foreach ($env as $key => $value) {
        putenv("{$key}={$value}");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('America/Bogota');
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
    $departamento = $data['departamento'] ?? '';
    $ciudad = $data['ciudad'] ?? '';
    $tipo_documento = $data['tipo_documento'] ?? '';
    $numero_documento = $data['numero_documento'] ?? '';
    $tipo_programa = $data['tipo_programa'] ?? '';
    $modalidad = $data['modalidad'] ?? '';
    $sede = $data['sede'] ?? '';
    $programa = $data['programa'] ?? '';
    $contacto_preferido = $data['contacto_preferido'] ?? '';
    $tratamiento_datos = isset($data['tratamiento_datos']) ? (int)$data['tratamiento_datos'] : 0;
    $acepta_terminos = isset($data['acepta_terminos']) ? (int)$data['acepta_terminos'] : 0;

    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_user = getenv('DB_USER') ?: '';
    $db_pass = getenv('DB_PASS') ?: '';
    $db_name = getenv('DB_NAME') ?: '';
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
    
    
    $fecha_registro = date('Y-m-d H:i:s');
    $stmt = $conn->prepare('INSERT INTO usuarios (nombre, apellido, email, celular, departamento, ciudad, tipo_documento, numero_documento, tipo_programa, modalidad, sede, programa, contacto_preferido, tratamiento_datos, acepta_terminos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssssssssssssi', $nombre, $apellido, $email, $celular, $departamento, $ciudad, $tipo_documento, $numero_documento, $tipo_programa, $modalidad, $sede, $programa, $contacto_preferido, $tratamiento_datos, $acepta_terminos);
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
