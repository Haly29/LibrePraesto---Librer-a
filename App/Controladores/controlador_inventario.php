<?php
require_once '../modelos/clase_inventario.php';
require_once '../../Config/conexion.php'; 

class ControladorInventario {
    private $modelo;
    private $db; // Agregar la conexión a la base de datos

    public function __construct() {
        $this->modelo = new ClaseInventario();
        $conexion = new connect(); // Crear una nueva conexión
        $this->db = $conexion->getConnection(); // Obtener la conexión de la clase `connect`
    }

    public function obtenerInventario($sucursal, $pagina) {
        return $this->modelo->obtenerInventario($sucursal, $pagina);
    }

    public function obtenerLibroPorCodigo($codigo) {
        $query = "SELECT * FROM libros WHERE codigo = ?";
        $stmt = $this->db->prepare($query); // Ahora `$this->db` está correctamente inicializado
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->db->error);
        }
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Retorna una fila asociativa o null si no hay resultados
    }
}
?>
