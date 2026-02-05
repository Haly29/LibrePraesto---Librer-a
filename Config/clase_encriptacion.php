<?php
class DatabaseEncript {
    private $dbHost = "localhost";
    private $dbUser = "adminysql";
    private $dbPassword = "admin";
    private $dbName = "libreria";

    // Método para inicializar y devolver la conexión
    private function getConnection() {
        $connection = new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);
        if ($connection->connect_error) {
            die("Error de conexión: " . $connection->connect_error);
        }
        return $connection;
    }

    // Método para ejecutar una consulta SQL directa
    public function executeQuery($sql) {
        $connection = $this->getConnection();
        $result = $connection->query($sql);
        $connection->close();
        return $result;
    }

    // Método para ejecutar una consulta preparada
    public function executePreparedStatement($sql, $paramTypes, ...$params) {
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);

        if ($stmt === false) {
            error_log("Error al preparar la consulta: " . $connection->error);
            die("Error al preparar la consulta.");
        }

        $stmt->bind_param($paramTypes, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            error_log("Error al ejecutar la consulta: " . $stmt->error);
        }

        $stmt->close();
        $connection->close();

        return $result;
    }

    // Método para insertar datos usando una consulta preparada
    public function insertData($tableName, $columns, $values) {
        $connection = $this->getConnection();
        $columns = $columns ? "($columns)" : "";
        $sql = "INSERT INTO $tableName $columns VALUES ($values)";
        $result = $connection->query($sql);
        if (!$result) {
            echo "<script>alert('Error al insertar datos');</script>";
        }
        $connection->close();
        return $result;
    }
}
?>