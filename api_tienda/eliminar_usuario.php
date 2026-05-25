<?php

/**
 * Servicio web para eliminar usuarios del sistema.
 * Recibe el ID del usuario mediante el método HTTP DELETE.
 * Proyecto: API Tienda de Motociclistas.
 */

require_once "config.php";
require_once "Database.php";

if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
    http_response_code(405);
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Método no permitido. Use DELETE."
    ]);
    exit();
}

/*
 * En PHP, los datos enviados por DELETE no llegan automáticamente a $_POST.
 * Por eso se leen desde php://input y se convierten en variables.
 */
parse_str(file_get_contents("php://input"), $datos);

$id = $datos["id"] ?? "";

if (empty($id)) {
    http_response_code(400);
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Debe enviar el ID del usuario que desea eliminar."
    ]);
    exit();
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    // Verifica si el usuario existe antes de eliminarlo.
    $consulta = "SELECT id FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($consulta);
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode([
            "estado" => "error",
            "mensaje" => "No se encontró un usuario con el ID indicado."
        ]);
        exit();
    }

    // Elimina el usuario de la base de datos.
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $resultado = $stmt->execute([$id]);

    if ($resultado) {
        http_response_code(200);
        echo json_encode([
            "estado" => "ok",
            "mensaje" => "Usuario eliminado correctamente."
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "estado" => "error",
            "mensaje" => "No fue posible eliminar el usuario."
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Error al procesar la eliminación del usuario."
    ]);
}
?>