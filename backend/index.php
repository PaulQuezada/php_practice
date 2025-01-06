<?php

// Datos de conexión a la base de datos
$host = "mariadb";
$db_user = "root";
$db_password = "rootpassword";
$db_name = "mydatabase";

// Conexión a la base de datos
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Verifica la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Manejar diferentes URIs
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri === '/login' && $requestMethod === 'POST') {
    // Leer el cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);

    // Validar datos recibidos
    if (!isset($input['user']) || !isset($input['pwd'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        http_response_code(400); // Bad Request
        exit;
    }

    $username = $conn->real_escape_string($input['user']);
    $password = $conn->real_escape_string($input['pwd']);

    // Consulta a la base de datos
    $query = $conn->prepare("SELECT password FROM users WHERE user = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Validar la contraseña
        $row = $result->fetch_assoc();
        if ($row['password'] === $password) { // Cambiar password_verify en entornos reales("hash") 
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    }

    $query->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Endpoint no encontrado']);
    http_response_code(404); // Not Found
}

// Cerrar la conexión
$conn->close();
?>