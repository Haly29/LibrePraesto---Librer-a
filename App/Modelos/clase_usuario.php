<?php
class Usuario {
    private $dbHandler;
    private $table_name = "usuarios";

    public function __construct($dbHandler) {
        $this->dbHandler = $dbHandler;
    }

    public function verificarCredenciales($nombreUsuario, $clave) {
        try {
            $query = "SELECT * FROM usuarios WHERE correo = ?";
            $result = $this->dbHandler->executePreparedStatement($query, 's', $nombreUsuario);
        
            if ($result && $result->num_rows === 1) {
                $row = $result->fetch_assoc();
                
                // Verificar si la contraseña es en texto plano
                if ($this->esTextoPlano($row['contraseña'])) {
                    // Convertirla a hash y actualizarla
                    $claveHasheada = password_hash($row['contraseña'], PASSWORD_DEFAULT);
                    $updateQuery = "UPDATE usuarios SET contraseña = ? WHERE id_usuario = ?";
                    $this->dbHandler->executePreparedStatement($updateQuery, 'si', $claveHasheada, $row['id_usuario']);
                }
                
                // Verificar la contraseña usando password_verify
                if (password_verify($clave, $row['contraseña'])) {
                    $_SESSION['nombre_usuario'] = $row['nombre_usuario'];
                    $this->redirigir('../Vistas/MenuAdmin.php'); //CAMBIAR CUANDO SE TENGA LA PÁGINA
                } else {
                    error_log("Contraseña incorrecta para el usuario: $nombreUsuario");
                    $this->redirigir('../Vistas/login_administrador.php?error=login_invalido');
                }
            } else {
                error_log("Usuario no encontrado: $nombreUsuario");
                $this->redirigir('../Vistas/login_administrador.php?error=login_invalido');
            }
        
            $result->close();
        } catch (Exception $e) {
            error_log("Error al verificar credenciales: " . $e->getMessage());
            throw $e;
        }
    }
    
    private function esTextoPlano($password) {
        // Lógica para verificar si la contraseña está en texto plano
        return strlen($password) < 60; // El tamaño de un hash con password_hash() es mayor a 60
    }     

    private function redirigir($url) {
        header("Location: $url");
        exit();
    }

    public function getUsuarios($search = '', $page = 1, $records_per_page = 4) {
        $offset = ($page - 1) * $records_per_page;
        
        $where_clause = "";
        if (!empty($search)) {
            $search = $this->dbHandler->real_string($search);
            $where_clause = " WHERE u.nombre_usuario LIKE '%$search%' OR u.correo LIKE '%$search%'";
        }

        $query = "SELECT u.id_usuario, u.nombre_usuario, u.correo, u.estado, s.nombre as nombre_sucursal 
                 FROM " . $this->table_name . " u 
                 LEFT JOIN sucursales s ON u.sucursal_id = s.id_sucursal" . 
                 $where_clause . " 
                 ORDER BY u.id_usuario 
                 LIMIT ?, ?";

        $stmt = $this->dbHandler->executePreparedStatement($query, 'ii', $offset, $records_per_page);
        
        return $stmt;
    }

    public function getTotalRecords($search = '') {
        $where_clause = "";
        if (!empty($search)) {
            $search = $this->dbHandler->real_string($search);
            $where_clause = " WHERE nombre_usuario LIKE '%$search%' OR correo LIKE '%$search%'";
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . $where_clause;
        $result = $this->dbHandler->executeQuery($query);
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }
}
?>