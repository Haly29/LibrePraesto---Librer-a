<?php
session_start();

if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login_administrador.php");
    exit();
}

require_once '../../Config/conexion.php';
require_once '../Modelos/clase_usuario.php';

$database = new connect();
$usuario = new Usuario($database);

$correo = $_SESSION['nombre_usuario'];
$records_per_page = 4;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$result = $usuario->getUsuarios($search, $page, $records_per_page);
$total_records = $usuario->getTotalRecords($search);
$total_pages = ceil($total_records / $records_per_page);
$offset = ($page - 1) * $records_per_page;


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libre Praesto - Usuario</title>
    <link rel="stylesheet" type="text/css" href="../../Public/CSS/normalize.css">
    <link rel="stylesheet" type="text/css" href="../../Public/CSS/estilo.css">
    <style>
        
    </style>
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
                <li><a href="../Vistas/modulo_inventario.php">Inventario</a></li> <!-- Falta la página -->
                <li><a href="../Vistas/modulo_libros.php">Libros</a></li>
                <li>
                    <a class="btn-pedir-libro"><?php echo $correo ?></a>
                </li>
			</ul>
		</nav>
	</header>

    <!-- Sección principal con un banner -->
    <main id="Titulo--banner">
        <img src="../../Public/Imagenes/banner de inicio.jpeg" alt="Banner de la página soporte">
        <div class="Cuadrado_titutlo--banner">
            <p>Usuario</p>
        </div>
	</main>

    <div class="content">
        <h1 class="page-title">Modulo de Usuarios</h1>

        <div class="search-container">
            <form method="GET" action="">
                <input type="text" name="search" class="search-box" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Buscar por nombre de usuario o correo...">
            </form>
        </div>

        <table id="Tabla-Usuario">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nombre Usuario</th>
                    <th>Correo</th>
                    <th>Sucursal</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $count = $offset + 1;
                    while ($row = $result->fetch_assoc()) {
                        $estado_class = $row['estado'] == 'activo' ? 'estado-activo' : 'estado-inactivo';
                        echo "<tr>";
                        echo "<td>" . $count . "</td>";
                        echo "<td>" . htmlspecialchars($row['nombre_usuario']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nombre_sucursal'] ?? 'No asignada') . "</td>";
                        echo "<td><span class='estado-badge " . $estado_class . "'>" . 
                             htmlspecialchars($row['estado']) . "</span></td>";
                        echo "</tr>";
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='5'>No se encontraron registros</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=$i" . (!empty($search) ? "&search=$search" : "") . "'" . 
                     ($page == $i ? " class='active'" : "") . ">$i</a>";
            }
            ?>
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
</body>
</html>