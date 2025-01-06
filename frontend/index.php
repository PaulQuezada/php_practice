<?php
// Verificación si los datos han sido enviados por el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtención de los datos del formulario
    $user = $_POST['user'] ?? '';
    $pwd = $_POST['pwd'] ?? '';

    // Creación de un array con los datos a enviar
    $postData = [
        'user' => $user,
        'pwd' => $pwd
    ];

    // Conversión de los datos a JSON
    $jsonData = json_encode($postData);

    // Configura la solicitud cURL
    $ch = curl_init('http://php:8000/login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ]);

    // Ejecución de la solicitud y obtención de la respuesta
    $response = curl_exec($ch);

    // Manejo de errores en la solicitud
    if (curl_errno($ch)) {
        echo 'Error en la solicitud: ' . curl_error($ch);
    } else {
        // Decodificación para luego mostrar la respuesta
        $responseData = json_decode($response, true);
        if ($responseData && isset($responseData['success']) && $responseData['success'] === true) {
            echo '<script>window.location.href = "/dashboard.php";</script>';
        } else {
            echo 'Respuesta del servidor: ' . htmlspecialchars(json_encode($responseData));
        }
    }

    // Cierra cURL
    curl_close($ch);
}
?>

<div class="main_login">
    <form class="login" action="?/login" method="POST">
        <h1>
            Login
        </h1>
        <input aria-label="Username" name="user">
        <input aria-label="Password" name="pwd">
        <button type="submit">Log in</button>
    </form>
</div>

<style>
    :root {
        color-scheme: light dark;
    }

    .main_login {
        display: flex;
        height: 100vh;
        align-items: center;
        justify-content: center;
    }

    .login {
        display: flex;
        flex-direction: column;
        border: 3px solid;
        padding: 10px;
    }

    .login input {
        margin-top: 20px;
        width: 300px;
    }

    .login button {
        margin-top: 20px;
        margin-bottom: 40px;
    }

    .login h1 {
        text-align: center;
    }
</style>