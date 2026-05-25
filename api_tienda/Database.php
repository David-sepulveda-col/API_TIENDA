<?php

/**
 * Clase encargada de gestionar la conexión con la base de datos MySQL.
 * Proyecto: API Tienda de Motociclistas.
 */

class Database {

    private $host = "localhost";
    private $db_name = "tienda_motociclistas";
    private $username = "root";
    private $password = "Glovo1717*";
    public $conn;

    /**
     * Método que retorna una conexión activa usando PDO.
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $exception) {
            http_response_code(500);

            echo json_encode([
                "estado" => "error",
                "mensaje" => "No fue posible conectar con la base de datos."
            ]);

            exit();
        }

        return $this->conn;
    }
}
?>