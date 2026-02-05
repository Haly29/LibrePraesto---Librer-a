<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login_administrador.php");
    exit();
}

// Obtener el nombre de usuario de la sesión
$nombre_usuario = htmlspecialchars($_SESSION['nombre_usuario']);
$correo = $_SESSION['nombre_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Libros</title>
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
            <p>Usuarios</p>
        </div>
	</main>
    <div class="content">
    <h1 class="page-title">Modulo de Usuarios</h1>

    <!-- Barra de búsqueda -->
    <div class="search-container">
        <form method="GET" action="modulo_libros.php">
            <input class="search-box" type="text" name="buscar" placeholder="Buscar por código" value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
            <button class="button_book" type="submit">Buscar</button>
        </form>
    </div>

    <table id="Tabla-Usuario">
        <thead>
            <tr>
                <th>Código</th>
                <th>Libro</th>
                <th>Imagen</th>
                <th>Descripción</th>
                <th>Autor</th>
                <th>Género</th>
                <th>Unidades</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Incluir controlador
            include_once '../Modelos/clase_libros.php';
            $libros = new Libros();
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $buscar = isset($_GET['buscar']) ? $_GET['buscar'] : null;
            $resultado = $libros->obtenerLibrosPaginados($paginaActual, 5, $buscar);

            foreach ($resultado['libros'] as $libro) {
                $base_url = "/LibreriaProyecto/Public/Imagenes/";
                $thumb_src = $base_url . trim($libro['imagen_ubicacion']) . trim($libro['imagen_original']);

                echo "<tr>
                        <td>{$libro['codigo']}</td>
                        <td>{$libro['nombre']}</td>
                        <td><img src='{$thumb_src}' alt='Imagen del libro' width='50'></td>
                        <td>{$libro['descripcion']}</td>
                        <td>{$libro['autor']}</td>
                        <td>{$libro['categoria']}</td>
                        <td>{$libro['cantidad_disponible']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
    
    <!-- Paginación -->
    <div class="pagination">
        <?php
        for ($i = 1; $i <= $resultado['totalPaginas']; $i++) {
            echo "<a href='?pagina=$i" . ($buscar ? "&buscar=" . urlencode($buscar) : "") . "'>" . ($i === $paginaActual ? "<strong>$i</strong>" : $i) . "</a> ";
        }
        ?>
    </div>

    <!-- Botones -->
    <div class="summary--botones">
        <button class="button_PrestamoLibro" onclick="location.href='../Modelos/clase_libros.php?accion=exportar'">Exportar</button>
        <button class="button_PrestamoLibro" onclick="location.href='modulo_inventario.php'">Agregar Libro</button>
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
