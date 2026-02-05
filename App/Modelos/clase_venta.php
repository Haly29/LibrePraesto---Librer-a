<?php
class Venta {
    private $dbHandler;

    public function __construct($db) {
        $this->dbHandler = $db;
    }

    // Obtener todas las sucursales (sin cambios)
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

    // Obtener libros por sucursal (sin cambios)
    public function getLibrosPorSucursal($sucursal_id) {
        try {
            $query = "SELECT l.id_libro, l.nombre as titulo 
                     FROM libros l 
                     INNER JOIN inventario i ON l.id_libro = i.libro_id 
                     WHERE i.sucursal_id = ? AND i.cantidad_disponible > 0";
            $stmt = $this->dbHandler->executePreparedStatement($query, 'i', $sucursal_id);
            return $stmt;
        } catch(Exception $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            throw $e;
        }
    }

    // Obtener ventas del día con filtros
    public function getVentasDelDia($sucursal_id = null) {
        try {
            $query = "SELECT v.id_venta, 
                        CONCAT(c.primer_nombre, ' ', c.primer_apellido) as cliente, 
                        l.nombre as libro, 
                        v.cantidad, 
                        s.nombre as sucursal, 
                        v.fecha_venta as fecha,
                        (l.precio * v.cantidad) as total
                     FROM ventas v 
                     INNER JOIN clientes c ON v.cliente_id = c.id_cliente 
                     INNER JOIN libros l ON v.libro_id = l.id_libro 
                     INNER JOIN sucursales s ON v.sucursal_id = s.id_sucursal
                     WHERE 1=1"; // Always true condition to simplify parameter adding
            
            $params = [];
            $types = '';
            
            // Agregar filtro de sucursal si existe
            if ($sucursal_id) {
                $query .= " AND v.sucursal_id = ?";
                $params[] = $sucursal_id;
                $types .= 'i';
            }
            
            $query .= " ORDER BY v.fecha_venta DESC";
            
            // Ejecutar la consulta con o sin parámetros
            if (!empty($params)) {
                $stmt = $this->dbHandler->executePreparedStatement($query, $types, ...$params);
            } else {
                $stmt = $this->dbHandler->executeQuery($query);
            }
            
            return $stmt;
        } catch(Exception $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function getResumenVentas($sucursal_id = null) {
        try {
            $query = "SELECT 
                     COUNT(DISTINCT cliente_id) as total_clientes,
                     SUM(cantidad) as total_libros,
                     SUM(l.precio * v.cantidad) as total_ventas
                     FROM ventas v
                     INNER JOIN libros l ON v.libro_id = l.id_libro
                     WHERE 1=1"; // Always true condition to simplify parameter adding
            
            $params = [];
            $types = '';
            
            // Agregar filtro de sucursal si existe
            if ($sucursal_id) {
                $query .= " AND v.sucursal_id = ?";
                $params[] = $sucursal_id;
                $types .= 'i';
            }
            
            // Ejecutar la consulta con o sin parámetros
            if (!empty($params)) {
                $stmt = $this->dbHandler->executePreparedStatement($query, $types, ...$params);
            } else {
                $stmt = $this->dbHandler->executeQuery($query);
            }
            
            return $stmt->fetch_assoc();
        } catch(Exception $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            throw $e;
        }
    }    
}