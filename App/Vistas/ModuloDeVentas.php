<?php
session_start();

if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login_administrador.php");
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'];

require_once '../../Config/conexion.php';
require_once '../Modelos/clase_venta.php';

$database = new connect();
$ventas = new Venta($database);

// Obtener datos para los selects
$sucursales = $ventas->getSucursales();

// Obtener los parámetros de filtrado
$sucursal_id = isset($_GET['sucursal_id']) ? $_GET['sucursal_id'] : null;

// Obtener los datos filtrados
$ventas_dia = $ventas->getVentasDelDia($sucursal_id);
$resumen = $ventas->getResumenVentas($sucursal_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libre Praesto - Módulo de Ventas</title>
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
                <li><a href="../Vistas/MenuAdmin.php">Menú</a></li>
                <li><a href="../Vistas/ModuloUsuario.php">Usuario</a></li>
                <li><a href="../Vistas/ModuloRegistroCliente.php">Registro cliente</a></li>
                <li><a href="../Vistas/ModuloDeVentas.php">Ventas</a></li>
                <li><a href="../Vistas/modulo_inventario.php">Inventario</a></li>
                <li><a href="../Vistas/modulo_libros.php">Libros</a></li>
                <li>
                    <a class="btn-pedir-libro"><?php echo $nombre_usuario; ?></a>
                </li>
			</ul>
		</nav>
	</header>

    <!-- Sección principal con un banner -->
    <main id="Titulo--banner">
        <img src="../../Public/Imagenes/banner de inicio.jpeg" alt="Banner de la página soporte">
        <div class="Cuadrado_titutlo--banner">
            <p>Ventas</p>
        </div>
	</main>

    <div class="content">
        <h1 class="page-title">Módulo de Ventas</h1>

        <div>
            <div class="selectors">
                <label class="input-ventas" for="sucursal">Sucursal:</label>
                <select class="select--Ventas" name="sucursal" onchange="cargarFiltro(this.value)">
                    <option value="">Todas las sucursales</option>
                    <?php
                    if ($sucursales) {
                        while ($row = $sucursales->fetch_assoc()) {
                            $selected = ($row['sucursal_id'] == $sucursal_id) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($row['sucursal_id']) . '" ' . $selected . '>' . 
                                htmlspecialchars($row['nombre']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <table id="Tabla-Usuario">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Libro</th>
                    <th>Cantidad</th>
                    <th>Sucursal</th>
                    <th>Fecha</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($ventas_dia) {
                    while ($row = $ventas_dia->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['cliente']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['libro']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['cantidad']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['sucursal']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
                        echo "<td>$" . number_format($row['total'], 2) . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>

        <div class="summary">
            <div>Cantidad de libros vendidos: <?php echo $resumen['total_libros'] ?? 0; ?></div>
            <div>Cantidad de clientes: <?php echo $resumen['total_clientes'] ?? 0; ?></div>
            <div>Total Ventas: $<?php echo number_format($resumen['total_ventas'] ?? 0, 2); ?></div>
        </div>
    </div>

    <!-- Pie de página -->
    <footer>
		<div class="logo_footer">
			<img src="../../Public/Imagenes/Logo.png" alt="Libre Praesto Logo">
		</div>
        <nav id="PiePag">
		    <a href="../../App/Modelos/modulo_salir.php" class="logout-link">Cerrar sesión</a>
			<p id="copyright">© 2024 Libre Praesto. Todos los derechos reservados.</p>	
		</nav>
	</footer>

    <script>
        function cargarFiltro(sucursal_id) {
            let url = 'ModuloDeVentas.php';
            
            // Agregar filtro de sucursal si está seleccionado
            if (sucursal_id) {
                url += '?sucursal_id=' + sucursal_id;
            }
            
            window.location.href = url;
        }
    </script>
</body>
</html>