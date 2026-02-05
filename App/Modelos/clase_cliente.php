<?php
class Cliente {
    private $dbHandler;

    public function __construct($dbHandler) {
        $this->dbHandler = $dbHandler;
    }

    public function verificarCredenciales($nombreUsuario, $clave) {
        try {
            $query = "SELECT id_cliente, contraseña FROM clientes WHERE usuario = ?";
            // Utilizamos consultas preparadas para prevenir inyección SQL
            $result = $this->dbHandler->executePreparedStatement($query, 's', $nombreUsuario);
        
            if ($result && $result->num_rows === 1) {
                $row = $result->fetch_assoc();
                
                // Verificar si la contraseña es en texto plano
                if ($this->esTextoPlano($row['contraseña'])) {
                    // Convertirla a hash y actualizarla
                    $claveHasheada = password_hash($row['contraseña'], PASSWORD_DEFAULT);
                    $updateQuery = "UPDATE clientes SET contraseña = ? WHERE id_cliente = ?";
                    $this->dbHandler->executePreparedStatement($updateQuery, 'si', $claveHasheada, $row['id_cliente']);
                }
                
                // Verificar la contraseña usando password_verify
                if (password_verify($clave, $row['contraseña'])) {
                    $_SESSION['libreria'] = $nombreUsuario;
                    return true; // Las credenciales son correctas
                } else {
                    error_log("Contraseña incorrecta para el usuario: $nombreUsuario");
                    return false; // Contraseña incorrecta
                }
            } else {
                error_log("Usuario no encontrado: $nombreUsuario");
                return false; // Usuario no encontrado
            }
        
            $result->close();
        } catch (Exception $e) {
            error_log("Error al verificar credenciales: " . $e->getMessage());
            throw $e; // Propagar la excepción
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

    public function getSucursales() {
        try {
            $query = "SELECT id_sucursal AS sucursal_id, nombre FROM sucursales";
            $stmt = $this->dbHandler->executeQuery($query);
            return $stmt;
        } catch(Exception $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            throw $e;
        }
    }

    public function getClienteData($idCliente) {
        try {
            $query = "SELECT * FROM clientes WHERE id_cliente = ?";
            $result = $this->dbHandler->executePreparedStatement($query, 'i', $idCliente);

            if ($result && $result->num_rows === 1) {
                return $result->fetch_assoc(); // Retorna los datos del usuario
            } else {
                return null;
            }
        } catch (Exception $e) {
            error_log("Error al obtener los datos del usuario: " . $e->getMessage());
            throw $e;
        }
    }

    public function getClientes($search = '', $page = 1, $records_per_page = 10, $sucursal_id = null) {
        $offset = ($page - 1) * $records_per_page;
        
        $query = "SELECT c.*, s.nombre 
                  FROM clientes c 
                  JOIN sucursales s ON c.sucursal_id = s.id_sucursal 
                  WHERE 1=1";
        
        // Agregar filtro de búsqueda
        if (!empty($search)) {
            $query .= " AND (c.primer_nombre LIKE '%$search%' OR c.cip LIKE '%$search%')";
        }
        
        // Agregar filtro de sucursal
        if ($sucursal_id !== null) {
            $query .= " AND c.sucursal_id = '$sucursal_id'";
        }
        
        $query .= " LIMIT $offset, $records_per_page";
        
        return $this->dbHandler->executeQuery($query);
    }
    
    // También necesitas modificar el método getTotalRecords
    public function getTotalRecords($search = '', $sucursal_id = null) {
        $query = "SELECT COUNT(*) AS total 
                  FROM clientes c 
                  JOIN sucursales s ON c.sucursal_id = s.id_sucursal 
                  WHERE 1=1";
        
        if (!empty($search)) {
            $query .= " AND (c.primer_nombre LIKE '%$search%' OR c.cip LIKE '%$search%')";
        }
        
        if ($sucursal_id !== null) {
            $query .= " AND c.sucursal_id = '$sucursal_id'";
        }
        
        $result = $this->dbHandler->executeQuery($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    

    public function getClientDataById($id_cliente) {
        $query = "SELECT * FROM clientes WHERE id_cliente = ?";
        $stmt = $this->dbHandler->executePreparedStatement($query, 'i', $id_cliente);
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Devolver una fila asociativa
    }
    

    public function updateUserData($id, $cedula, $nombre, $segundoNombre, $apellido, $segundoApellido, $fechaNacimiento, $sucursal) {
        try {
            $query = "UPDATE clientes SET cip = ?, primer_nombre = ?, segundo_nombre = ?, primer_apellido = ?, segundo_apellido = ?, fecha_nacimiento = ?, sucursal_id = ? WHERE id_cliente = ?";
            $this->dbHandler->executePreparedStatement($query, 'ssssssii', $cedula, $nombre, $segundoNombre, $apellido, $segundoApellido, $fechaNacimiento, $sucursal, $id);
        } catch (Exception $e) {
            error_log("Error al actualizar los datos del usuario: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateUserPassword($id, $hashedPassword) {
        try {
            $query = "UPDATE clientes SET contraseña = ? WHERE id_cliente = ?";
            $this->dbHandler->executePreparedStatement($query, 'si', $hashedPassword, $id);
        } catch (Exception $e) {
            error_log("Error al actualizar la contraseña del usuario: " . $e->getMessage());
            throw $e;
        }
    }
}
?>