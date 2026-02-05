<?php 
    class  connect{
        private $dbHost = "localhost";
        private $dbUser = "adminysql";
        private $dbPassword = "admin";
        private $dbName = "libreria";
    
        // Propiedad para la conexión
        private $conn;
        
        public function __construct() {
            $this->conn = new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }
    
        public function getConnection() {
            return $this->conn;
        }

        public function real_string($string){
            return $this->conn->real_escape_string($string);
        }
    
        // Método para ejecutar consultas SQL directas
        public function executeQuery($query) {
            $result = $this->conn->query($query);
            if ($result === false) {
                throw new Exception("Error en la consulta: " . $this->conn->error);
            }
            return $result;
        }
    
        // Método para ejecutar una consulta preparada
        public function executePreparedStatement($query, $types = '', ...$params) {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                throw new Exception("Error en la consulta: " . $this->conn->error);
            }
            
            // Contar el número de marcadores en la consulta
        $numMarkers = substr_count($query, '?');
        if ($numMarkers !== count($params)) {
            throw new Exception("Error: El número de parámetros proporcionados (" . count($params) . ") no coincide con los marcadores en la consulta ($numMarkers).");
        }
        
            // Solo enlaza los parámetros si hay tipos especificados
            if ($types) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            return $stmt->get_result();
        }        
    
        // Método para insertar datos usando una consulta preparada
        public function insertData($tableName, $columns, $values) {
            $columns = $columns ? "($columns)" : "";
            $sql = "INSERT INTO $tableName $columns VALUES ($values)";
            return $this->conn->query($sql);
        }

        public function getBestSellers() {
            $query = "SELECT titulo, ventas FROM libros ORDER BY ventas DESC LIMIT 5";
            $result = $this->executeQuery($query);

            // Array para almacenar los resultados
            $bestSellers = [];
            while ($row = $result->fetch_assoc()) {
                $bestSellers[] = $row;
            }

            return json_encode($bestSellers);
        }

        
    
        // Destructor para cerrar la conexión cuando el objeto es destruido
        public function __destruct() {
            if ($this->conn) {
                $this->conn->close();
            }
        }
    }  
?>