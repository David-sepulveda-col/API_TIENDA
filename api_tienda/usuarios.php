<?php

/**
 * Servicio web para consultar los usuarios registrados.
 * Se utiliza como apoyo para verificar los datos almacenados.
 */

require_once "config.php";
require_once "Database.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Método no permitido. Use GET."
    ]);
    exit();
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    // Consulta los usuarios registrados sin mostrar la contraseña.
    $sql = "SELECT id, usuario, nombres, apellidos, celular, rol, fecha_registro 
            FROM usuarios 
            ORDER BY id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        "estado" => "ok",
        "total" => count($usuarios),
        "usuarios" => $usuarios
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "estado" => "error",
        "mensaje" => "No fue posible consultar los usuarios registrados."
    ]);
}
?>