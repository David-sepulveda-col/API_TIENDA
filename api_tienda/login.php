<?php

/**
 * Servicio web para iniciar sesión.
 * Recibe usuario y contraseña mediante POST.
 * Devuelve autenticación satisfactoria si los datos son correctos.
 */

require_once "config.php";
require_once "Database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Método no permitido. Use POST."
    ]);
    exit();
}

$usuario = $_POST["usuario"] ?? "";
$password = $_POST["password"] ?? "";

if (empty($usuario) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Debe ingresar usuario y contraseña."
    ]);
    exit();
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    // Consulta el usuario con las credenciales recibidas.
    $sql = "SELECT id, usuario, nombres, apellidos, celular, rol 
            FROM usuarios 
            WHERE usuario = ? AND password = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$usuario, $password]);

    if ($stmt->rowCount() > 0) {
        $datosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode([
            "estado" => "ok",
            "mensaje" => "Autenticación satisfactoria.",
            "usuario" => $datosUsuario
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            "estado" => "error",
            "mensaje" => "Error en la autenticación."
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Error al procesar el inicio de sesión."
    ]);
}
?>