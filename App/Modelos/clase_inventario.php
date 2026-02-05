<?php
require_once '../../Config/conexion.php';

class ClaseInventario {
    private $db;

    public function __construct() {
        $this->db = new connect();
    }

    // Obtener datos del inventario con paginación y filtro por sucursal
    public function obtenerInventario($sucursal = null, $pagina = 1, $itemsPorPagina = 5) {
        $offset = ($pagina - 1) * $itemsPorPagina;
        $params = [];
        $types = '';
        $where = '';

        if ($sucursal) {
            $where = "WHERE inventario.sucursal_id = ?";
            $params[] = $sucursal;
            $types .= 'i';
        }

        $query = "SELECT 
                    libros.codigo, inventario.cantidad_disponible, sucursales.nombre AS sucursal,
                    inventario.estado, libros.autor, libros.categoria, libros.precio
                  FROM inventario
                  JOIN libros ON inventario.id_libro = libros.id_libro
                  JOIN sucursales ON inventario.sucursal_id = sucursales.id_sucursal
                  $where
                  LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $itemsPorPagina;
        $types .= 'ii';

        $result = $this->db->executePreparedStatement($query, $types, ...$params);

        // Obtener total de filas para paginación
        $totalQuery = "SELECT COUNT(*) AS total FROM inventario $where";
        if ($sucursal) {
            $resultTotal = $this->db->executePreparedStatement($totalQuery, 'i', $sucursal);
        } else {
            $resultTotal = $this->db->executePreparedStatement($totalQuery);
        }
        $totalItems = $resultTotal->fetch_assoc()['total'];

        return [
            'inventario' => $result->fetch_all(MYSQLI_ASSOC),
            'totalPaginas' => ceil($totalItems / $itemsPorPagina)
        ];
    }

    public function registrarLibroCompleto($datosLibro, $sucursalId, $archivoImagen) {
        $rutaBase = "../../Public/Imagenes/nuevo_autor/";
        $nombreOriginal = $archivoImagen['name'];
        $formatoImagen = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
        $formatosPermitidos = ['jpg', 'jpeg', 'png', 'gif'];
    
        // Validar formato de imagen
        if (!in_array(strtolower($formatoImagen), $formatosPermitidos)) {
            throw new Exception("El formato de la imagen no es válido. Formatos permitidos: JPG, JPEG, PNG, GIF.");
        }
    
        // Validar tamaño de imagen (máximo 10 MB)
        if ($archivoImagen['size'] > 10 * 1024 * 1024) {
            throw new Exception("El tamaño de la imagen supera el límite permitido de 10 MB.");
        }
    
        // Mover el archivo a la ubicación deseada
        $ubicacionImagen = $rutaBase . $nombreOriginal;
        if (!move_uploaded_file($archivoImagen['tmp_name'], $ubicacionImagen)) {
            throw new Exception("Error al subir la imagen.");
        }
    
        // Crear el thumbnail (Ejemplo usando GD)
        $rutaThumbnail = $rutaBase . pathinfo($nombreOriginal, PATHINFO_FILENAME) . "_thumb." . $formatoImagen;
        if (!copy($ubicacionImagen, $rutaThumbnail)) {
            throw new Exception("Error al crear el thumbnail.");
        }
    
        // Agregar solo la ruta de la carpeta al array $datosLibro
        $datosLibro['imagen_thumbnail'] = $rutaThumbnail;
        $datosLibro['imagen_original'] = $nombreOriginal;
        $datosLibro['imagen_formato'] = $formatoImagen;
        $datosLibro['imagen_ubicacion'] = $rutaBase; // Solo la ruta de la carpeta
    
        // Registrar el libro
        $queryLibro = "INSERT INTO libros 
                        (codigo, nombre, descripcion, autor, categoria, cantidad_disponible, precio, 
                         imagen_thumbnail, imagen_original, imagen_formato, imagen_ubicacion)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->db->executePreparedStatement(
            $queryLibro,
            'sssssidssss',
            $datosLibro['codigo'], $datosLibro['nombre'], $datosLibro['descripcion'],
            $datosLibro['autor'], $datosLibro['categoria'], $datosLibro['cantidad_disponible'],
            $datosLibro['precio'], $datosLibro['imagen_thumbnail'], $datosLibro['imagen_original'],
            $datosLibro['imagen_formato'], $datosLibro['imagen_ubicacion']
        );
    
        // Obtener el ID del libro insertado
        $idLibro = $this->db->getConnection()->insert_id;
    
        // Registrar el inventario
        $queryInventario = "INSERT INTO inventario (id_libro, sucursal_id, cantidad_disponible, estado) VALUES (?, ?, ?, ?)";
        $this->db->executePreparedStatement(
            $queryInventario,
            'iiis',
            $idLibro, $sucursalId, $datosLibro['cantidad_disponible'], 'Normal'
        );
    
        return true;
    }      
        

    
    public function actualizarLibro($idLibro, $datos) {
        $queryLibro = "UPDATE libros 
                       SET codigo = ?, nombre = ?, cantidad_disponible = ?, autor = ?, precio = ?, categoria = ? 
                       WHERE id_libro = ?";
        $this->db->executePreparedStatement(
            $queryLibro,
            'ssidsii',
            $datos[0], $datos[1], $datos[2], $datos[3], $datos[5], $datos[6], $idLibro
        );
    
        $queryInventario = "UPDATE inventario 
                            SET sucursal_id = ?, cantidad_disponible = ? 
                            WHERE id_libro = ?";
        $this->db->executePreparedStatement(
            $queryInventario,
            'iii',
            $datos[4], $datos[2], $idLibro
        );
    
        return true;
    }

    public function obtenerLibroPorCodigo($codigo) {
        $query = "SELECT 
                    libros.id_libro, libros.codigo, libros.nombre, libros.categoria, 
                    libros.autor, libros.precio, inventario.cantidad_disponible, 
                    inventario.sucursal_id
                  FROM libros
                  JOIN inventario ON libros.id_libro = inventario.id_libro
                  WHERE libros.codigo = ?";
        $result = $this->db->executePreparedStatement($query, 's', $codigo);
        return $result->fetch_assoc();
    }

    public function actualizarLibroInventario($datosLibro, $datosInventario) {
        $queryLibro = "UPDATE libros 
                       SET nombre = ?, categoria = ?, autor = ?, precio = ? 
                       WHERE codigo = ?";
        $this->db->executePreparedStatement(
            $queryLibro,
            'sssds',
            $datosLibro['nombre'], $datosLibro['categoria'], 
            $datosLibro['autor'], $datosLibro['precio'], $datosLibro['codigo']
        );
    
        $queryInventario = "UPDATE inventario 
                            SET sucursal_id = ?, cantidad_disponible = ? 
                            WHERE id_libro = (SELECT id_libro FROM libros WHERE codigo = ?)";
        $this->db->executePreparedStatement(
            $queryInventario,
            'iis',
            $datosInventario['sucursal_id'], $datosInventario['cantidad_disponible'], 
            $datosLibro['codigo']
        );
    
        return true;
    }
    
}
