<?php

/**
 * Archivo de configuración general para la API.
 * Proyecto: Tienda virtual de artículos para motociclistas.
 */

// Permite que la API responda en formato JSON.
header("Content-Type: application/json; charset=UTF-8");

// Permite solicitudes desde cualquier origen durante las pruebas locales.
header("Access-Control-Allow-Origin: *");

// Permite los métodos HTTP necesarios para probar la API.
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Permite encabezados básicos en las solicitudes.
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejo de solicitudes OPTIONS usadas por algunos clientes HTTP.
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}
?>