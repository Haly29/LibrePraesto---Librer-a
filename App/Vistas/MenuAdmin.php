<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login_administrador.php");
    exit();
}

// Obtener el nombre de usuario de la sesión
$nombre_usuario = htmlspecialchars($_SESSION['nombre_usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libre Praesto - Menú Principal</title>
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
                <li>
                    <a class="btn-pedir-libro"><?php echo $nombre_usuario ?></a>
                </li>
			</ul>
		</nav>
	</header>

    <!-- Sección principal con un banner -->
    <main id="Titulo--banner">
        <img width="1600px" src="../../Public/Imagenes/banner de inicio.jpeg" alt="Banner de la página soporte">
        <div class="Cuadrado_titutlo--banner">
            <p>Menú</p>
        </div>
	</main>

    <main class="modules-container">
        <a href="../Vistas/ModuloUsuario.php" class="module-card">
            <div class="module-icon">
                <img class="img--menuAdmin" src="../../Public/Imagenes/icon-user.png" alt="Icono del módulo Usuario">
            </div>
            <h2 class="module-name">Módulo Usuario</h2>
        </a>

        <a href="../Vistas/ModuloRegistroCliente.php" class="module-card">
            <div class="module-icon">
                <img class="img--menuAdmin" src="../../Public/Imagenes/icon-registro.png" alt="Icono del módulo registro cliente">
            </div>
            <h2 class="module-name">Módulo Registro Cliente</h2>
        </a>

        <a href="../Vistas/ModuloDeVentas.php" class="module-card">
            <div class="module-icon">
                <img class="img--menuAdmin" src="../../Public/Imagenes/icon-ventas.png" alt="Icono del módulo de ventas">
            </div>
            <h2 class="module-name">Módulo de Ventas</h2>
        </a>

        <a href="../Vistas/modulo_inventario.php" class="module-card">
            <div class="module-icon">
                <img class="img--menuAdmin" src="../../Public/Imagenes/icon-inventario.png" alt="Icono del módulo inventario">
            </div>
            <h2 class="module-name">Módulo Inventario</h2>
        </a>

        <a href="../Vistas/modulo_libros.php" class="module-card">
            <div class="module-icon">
                <img class="img--menuAdmin" src="../../Public/Imagenes/icon-book.png" alt="Icono del módulo libro">
            </div>
            <h2 class="module-name">Módulo Libros</h2>
        </a>
    </main>

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
        // Prevenir navegación atrás después de logout
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>