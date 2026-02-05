<?php
// Incluir archivo de conexión
include('../../Config/conexion.php');
session_start(); // Inicia o reanuda la sesión

// Crear una instancia de la clase connect
$database = new connect();
$conn = $database->getConnection();

// Verificar si el usuario está logueado
if (!isset($_SESSION['libreria'])) {
    header("Location: login_cliente.php");
    exit();
}

// Obtener el nombre de usuario desde la sesión
$nombre_usuario = $_SESSION['libreria'];

// Obtener el ID del cliente basado en el nombre de usuario
$sql_cliente = "SELECT id_cliente FROM clientes WHERE usuario = ?";
$stmt_cliente = $conn->prepare($sql_cliente);
$stmt_cliente->bind_param('s', $nombre_usuario);
$stmt_cliente->execute();
$result_cliente = $stmt_cliente->get_result();
$row_cliente = $result_cliente->fetch_assoc();

if (!$row_cliente) {
    header("Location: login_cliente.php");
    exit();
}

$id_cliente = $row_cliente['id_cliente'];

// Preparar la consulta para obtener los datos del historial de compras
$sql = "SELECT libros.nombre AS libro, compras.cantidad, libros.descripcion, sucursales.id_sucursal, sucursales.nombre AS sucursal, compras.fecha_compra
        FROM compras
        JOIN libros ON compras.libro_id = libros.id_libro
        JOIN sucursales ON compras.sucursal_id = sucursales.id_sucursal
        WHERE compras.cliente_id = ?";

// Preparar la declaración
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compra</title>
    <link rel="stylesheet" type="text/css" href="../../Public/CSS/normalize.css">
	<link rel="stylesheet" type="text/css" href="../../Public/CSS/estilo.css">
</head>
<body>
    <!-- Encabezado de la página -->
    <header>
			<div class="logo_menu">
				<img src="../../Public/Imagenes/Logo.png" alt="Libre Praesto Logo">
			</div>
			<!-- Menú de navegación -->
			<nav>
				<ul>
					<li><a href="../Vistas/inicio.php">Inicio</a></li>
					<li><a href="../Vistas/carrito.php">Carrito</a></li>
					<li><a href="../Vistas/HistorialCompra.php">Historial de Compra</a></li>
					<li><a href="../Vistas/soporte.php">Soporte</a></li>

					<li>
						<a href="../Vistas/Catalogo.php" class="btn-pedir-libro">Catálogo</a>
					</li>
				</ul>
			</nav>
	</header>

    <!-- Sección principal con un banner -->
    <main id="Titulo--banner">
        <img src="../../Public/Imagenes/banner de inicio.jpeg" alt="Banner de la página soporte">
	</main>

    <div class="content">
        <h1 class="page-title">Historial de Compra</h1>
        <p>Consulta los libros que has adquirido en nuestro sistema.</p>
        <table id="Tabla-Usuario">
            <thead>
                <tr>
                    <th>Libro</th>
                    <th>Cantidad</th>
                    <th>Descripción</th>
                    <th>Sucursal</th>
                    <th>Fecha de compra</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verificar si hay resultados
                if ($result->num_rows > 0) {
                    // Mostrar los datos de la consulta
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['libro']) . "</td>
                                <td>" . htmlspecialchars($row['cantidad']) . "</td>
                                <td>" . htmlspecialchars($row['descripcion']) . "</td>
                                <td>" . htmlspecialchars($row['sucursal']) . "</td>
                                <td>" . htmlspecialchars($row['fecha_compra']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay registros disponibles</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

        <!-- Pie de página -->
        <footer>
			<div class="logo_footer">
				<img src="../../Public/Imagenes/Logo.png" alt="Libre Praesto Logo">
			</div>
            <nav id="PiePag">
                <ul id="Submenu">
                    <li class="LinkSubmenu"><a href="../Vistas/inicio.php">Inicio</a></li>
                    <li class="LinkSubmenu"><a href="../Vistas/carrito.php">Carrito</a></li>
                    <li class="LinkSubmenu"><a href="../Vistas/HistorialCompra.php">Historial de Compra</a></li>
                    <li class="LinkSubmenu"><a href="../Vistas/soporte.php">Soporte</a></li>
                </ul>
				<a href="../../App/Modelos/modulo_salir.php" class="logout-link">Cerrar sesión</a>
				<p id="copyright">© 2024 Libre Praesto. Todos los derechos reservados.</p>	
			</nav>
		</footer>
</body>
</html>