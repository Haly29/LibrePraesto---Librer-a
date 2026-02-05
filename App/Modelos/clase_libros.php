<?php
require_once '../../Config/conexion.php'; // Clase de conexión
require '../../Config/vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Libros {
    private $db;

    public function __construct() {
        $this->db = new connect();
    }

    public function obtenerLibrosPaginados($pagina, $librosPorPagina) {
        $offset = ($pagina - 1) * $librosPorPagina;
        $query = "SELECT * FROM libros LIMIT ?, ?";
        $result = $this->db->executePreparedStatement($query, 'ii', $offset, $librosPorPagina);

        $totalLibrosQuery = "SELECT COUNT(*) AS total FROM libros";
        $totalLibrosResult = $this->db->executeQuery($totalLibrosQuery);
        $totalLibros = $totalLibrosResult->fetch_assoc()['total'];

        return [
            'libros' => $result->fetch_all(MYSQLI_ASSOC),
            'totalPaginas' => ceil($totalLibros / $librosPorPagina)
        ];
    }

    public function exportarExcel() {
        $query = "SELECT codigo, nombre, descripcion, autor, categoria, cantidad_disponible FROM libros";
        $result = $this->db->executeQuery($query);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Libros');

        // Encabezados
        $sheet->setCellValue('A1', 'Código')
              ->setCellValue('B1', 'Libro')
              ->setCellValue('C1', 'Descripción')
              ->setCellValue('D1', 'Autor')
              ->setCellValue('E1', 'Género')
              ->setCellValue('F1', 'Unidades');

        // Filas
        $row = 2;
        while ($libro = $result->fetch_assoc()) {
            $sheet->setCellValue("A$row", $libro['codigo'])
                  ->setCellValue("B$row", $libro['nombre'])
                  ->setCellValue("C$row", $libro['descripcion'])
                  ->setCellValue("D$row", $libro['autor'])
                  ->setCellValue("E$row", $libro['categoria'])
                  ->setCellValue("F$row", $libro['cantidad_disponible']);
            $row++;
        }

        // Descarga
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="libros.xlsx"');
        $writer->save('php://output');
    }
}

// Manejo de acciones
if (isset($_GET['accion']) && $_GET['accion'] === 'exportar') {
    $libros = new Libros();
    $libros->exportarExcel();
}
?>
