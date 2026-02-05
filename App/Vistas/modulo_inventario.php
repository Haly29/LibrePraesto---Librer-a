<?php
require_once '../Controladores/controlador_inventario.php';
session_start();
// Verificar si el usuario está logueado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login_administrador.php");
    exit();
}

// Obtener el nombre de usuario de la sesión
$nombre_usuario = htmlspecialchars($_SESSION['nombre_usuario']);
$correo = $_SESSION['nombre_usuario'];

$controlador = new ControladorInventario();
$sucursal = $_GET['sucursal'] ?? null;
$pagina = $_GET['pagina'] ?? 1;

$datos = $controlador->obtenerInventario($sucursal, $pagina);
$inventario = $datos['inventario'];
$totalPaginas = $datos['totalPaginas'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo Inventario</title>
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
            <p>Inventario</p>
        </div>
	</main>

    <div class="content">
    <h1 class="page-title">Inventario de Libros</h1>

    <!-- Filtro por sucursal -->
    <div id="registroCliente-container">
    <form id="filtro-cliente" method="GET">
        <label for="sucursal">Filtrar por Sucursal:</label>
        <select name="sucursal" id="sucursal">
            <option value="">Todas</option>
            <option value="1" <?= $sucursal == 1 ? 'selected' : '' ?>>Central</option>
            <option value="2" <?= $sucursal == 2 ? 'selected' : '' ?>>Este</option>
        </select>
        <button type="submit">Filtrar</button>
    </form>
    </div>

    <!-- Tabla de inventario -->
    <table id="Tabla-Usuario">
        <thead>
            <tr>
                <th>Código</th>
                <th>Cantidad</th>
                <th>Sucursal</th>
                <th>Estado</th>
                <th>Autor</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventario as $item): ?>
                <tr>
                    <td><?= $item['codigo'] ?></td>
                    <td><?= $item['cantidad_disponible'] ?></td>
                    <td><?= $item['sucursal'] ?></td>
                    <td><?= $item['estado'] ?></td>
                    <td><?= $item['autor'] ?></td>
                    <td><?= $item['categoria'] ?></td>
                    <td><?= $item['precio'] ?></td>
                    <td>
                         <a class='button-editar' href="modificar_inventario.php?codigo=<?= $item['codigo'] ?>">Modificar</a>
                    </td>

                    </td>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="pagination">
        <?php if ($pagina > 1): ?>
            <a href="?pagina=<?= $pagina - 1 ?>&sucursal=<?= $sucursal ?>">Anterior</a>
        <?php endif; ?>

        Página <?= $pagina ?> de <?= $totalPaginas ?>

        <?php if ($pagina < $totalPaginas): ?>
            <a href="?pagina=<?= $pagina + 1 ?>&sucursal=<?= $sucursal ?>">Siguiente</a>
        <?php endif; ?>
    </div>

    <!-- Botón para registrar nuevo libro -->
    <div class="summary--botones">
        <a class="button_inventario" href="registrar_libro.php">Nuevo Libro</a>
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
