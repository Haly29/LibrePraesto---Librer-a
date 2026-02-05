<?php
session_start(); // Inicia o reanuda la sesión

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['libro_id'])) {
    $libro_id = intval($_POST['libro_id']);
	
	// Verificar si el usuario está logueado
	if (!isset($_SESSION['libreria'])) {
		header("Location: login_cliente.php");
		exit();
	}
    
    // Inicializa el carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Agrega el libro al carrito o incrementa la cantidad
    if (isset($_SESSION['carrito'][$libro_id])) {
        $_SESSION['carrito'][$libro_id] += 1;
    } else {
        $_SESSION['carrito'][$libro_id] = 1;
    }

    // Redirige para evitar reenvío del formulario
    header("Location: ../Vistas/libro.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Libro</title>
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
<main>
    <section class="book-details">
        <?php
        include('../../Config/conexion.php');

        $conexion = new connect();
        $conn = $conexion->getConnection();

        // Verificar si se pasa el ID del libro en la URL
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);

            // Consulta para obtener los detalles del libro seleccionado
            $sql = "SELECT * FROM libros WHERE id_libro = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                echo '<div class="libro-item">';
                $base_url = "http://localhost/LibreriaProyecto/Public/Imagenes/";
                $imagen_ubicacion = trim($row['imagen_ubicacion']);
                $imagen_original = trim($row['imagen_original']);
                $thumb_src = $base_url . $imagen_ubicacion . $imagen_original;

                if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/LibreriaProyecto/Public/Imagenes/" . $imagen_ubicacion . $imagen_original)) {
                    echo '<img class="imagen-libro" src="' . $thumb_src . '" alt="Imagen del libro">';
                } else {
                    echo '<p>No se encontró la imagen en la ubicación esperada.</p>';
                }
                echo '<div class="libro-content">';
                echo '<h1 class="titulo-book">' . htmlspecialchars($row['nombre']) . '</h1>';              
                echo '<p class="libro-autor">Autor: ' . htmlspecialchars($row['autor']) . '</p>';
                echo '<p id="cantidad-libro">Cantidad disponible: ' . htmlspecialchars($row['cantidad_disponible']) . '</p>';
                echo '<p>Descripción: ' . htmlspecialchars($row['descripcion']) . '</p>';
                echo '<div class="precio-book">Precio: $' . number_format($row['precio'], 2) . '</div>';

                // Formulario para agregar al carrito
					echo '<form method="post" action="libro.php">';
					echo '<input type="hidden" name="libro_id" value="' . htmlspecialchars($row['id_libro']) . '">';
					echo '<button type="submit" class="comprar-btn">Comprar</button>';
					echo '</form>';
                    echo '</div>';
                echo '</div>';
            } else {
                echo "<p>No se encontró el libro solicitado.</p>";
            }
            $stmt->close();
        } else {
            echo "<p>No se especificó un libro válido.</p>";
        }
        ?>
    </section>

    <hr>
				<div class="section-title">
					<h2 id="Titulo--Autor">Recomendaciones</h2>
				</div>
	<hr>
    <section class="display--grid inicio-espacio">
        <?php
        require_once('../../Config/conexion.php');

        $conexion = new connect();
        $conn = $conexion->getConnection();

        // Consulta para obtener libros recomendados (incluyendo id_libro)
        $sql = "SELECT id_libro, nombre, precio, imagen_ubicacion, imagen_original FROM libros ORDER BY RAND() LIMIT 4"; // Selecciona 4 libros al azar
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $base_url = "http://localhost/LibreriaProyecto/Public/Imagenes/";
                $imagen_ubicacion = trim($row['imagen_ubicacion']);
                $imagen_original = trim($row['imagen_original']);
                $thumb_src = $base_url . $imagen_ubicacion . $imagen_original;

                echo '<div class="book display_grid--row">';
                echo '
                        <img class="Portada--catalogo" src="' . htmlspecialchars($thumb_src) . '" alt="' . htmlspecialchars($row['nombre']) . '">
                        
                        <p class="Libro--catalogo">' . htmlspecialchars($row['nombre']) . '</p>
                        <div class="book--precio">
                        <p class="precio-libro">$' . number_format($row['precio'], 2) . '</p>

                ';
                // Formulario para agregar al carrito
                echo '<form method="post" action="libro.php">';
                echo '<input type="hidden" name="libro_id" value="' . htmlspecialchars($row['id_libro']) . '">';
                echo '<button class="button_AgregarLibro" type="submit" name="agregar_carrito">Comprar</button>';
                echo '</form>';
                echo '</div>';
                echo '<a href="libro.php?id=' . htmlspecialchars($row['id_libro']) . '" class="detalle-btn">Ver Detalles</a>';
                echo '</div>'; // Cierre del div libro
            }
        } else {
            echo '<p>No hay recomendaciones disponibles en este momento.</p>';
        }
        ?>
    </section>
</section>
</main>
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