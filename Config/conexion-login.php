<?php
class LoginDatabase {
    private $dbHost = "localhost";
    private $dbUsername = "comodin"; // Usuario para el login
    private $dbPassword = "12345";   // Contraseña para el login
    private $dbName = "libreria"; // Base de datos
    private $dbConnection;

    // Inicializar la conexión a la base de datos
    public function __construct() {
        $this->initializeConnection();
    }

    // Establecer la conexión
    private function initializeConnection() {
        if (!$this->dbConnection) {
            $this->dbConnection = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);

            if ($this->dbConnection->connect_error) {
                die("Error de conexión: " . $this->dbConnection->connect_error);
            }
        }
    }

    // Método para cerrar la conexión
    public function closeConnection() {
        if ($this->dbConnection) {
            $this->dbConnection->close();
            $this->dbConnection = null;
        }
    }

    // Ejecutar una consulta preparada
    public function executePreparedQuery($sql, $paramTypes, ...$params) {
        $this->initializeConnection();
        $stmt = $this->dbConnection->prepare($sql);

        if ($stmt === false) {
            die("Error al preparar la consulta: " . $this->dbConnection->error);
        }

        // Enlace de parámetros y ejecución
        $stmt->bind_param($paramTypes, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result;
    }
}
?>