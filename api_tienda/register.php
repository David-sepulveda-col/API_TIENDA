<?php

/**
 * Servicio web para registrar usuarios en la tienda virtual de motociclistas.
 * Recibe los datos mediante método POST y los almacena en la base de datos.
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
$nombres = $_POST["nombres"] ?? "";
$apellidos = $_POST["apellidos"] ?? "";
$celular = $_POST["celular"] ?? "";
$rol = $_POST["rol"] ?? "empleado";

if (empty($usuario) || empty($password) || empty($nombres) || empty($apellidos)) {
    http_response_code(400);
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Debe completar usuario, contraseña, nombres y apellidos."
    ]);
    exit();
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    // Verifica si el usuario ya existe en la base de datos.
    $consulta = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($consulta);
    $stmt->execute([$usuario]);

    if ($stmt->rowCount() > 0) {
        http_response_code(409);
        echo json_encode([
            "estado" => "error",
            "mensaje" => "El usuario ya se encuentra registrado."
        ]);
        exit();
    }

    // Registra el nuevo usuario.
    $sql = "INSERT INTO usuarios (usuario, password, nombres, apellidos, celular, rol)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $resultado = $stmt->execute([
        $usuario,
        $password,
        $nombres,
        $apellidos,
        $celular,
        $rol
    ]);

    if ($resultado) {
        http_response_code(201);
        echo json_encode([
            "estado" => "ok",
            "mensaje" => "Usuario registrado correctamente en la tienda de motociclistas."
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "estado" => "error",
            "mensaje" => "No fue posible registrar el usuario."
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Error al procesar el registro del usuario."
    ]);
}
?>