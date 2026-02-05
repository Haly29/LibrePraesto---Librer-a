<?php 
class ConnectAutores {
    private $dbHost = "localhost";
    private $dbUser = "adminysql";
    private $dbPassword = "admin";
    private $dbName = "libreria";
    private $conn;
    private static $instance;

    // Constructor privado para evitar múltiples instancias
    private function __construct() {
        $this->conn = new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Obtener la única instancia de conexión
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new ConnectAutores();
        }
        return self::$instance;
    }

    // Obtener la conexión
    public function getConnection() {
        return $this->conn;
    }
}
?>