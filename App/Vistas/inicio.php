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
    header("Location: ../Vistas/inicio.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Inicio - Libre Praesto</title>
		<link rel="stylesheet" type="text/css" href="../../Public/CSS/normalize.css">
		<link rel="stylesheet" type="text/css" href="../../Public/CSS/estilo.css">
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <div class="Cuadrado_titutlo--banner">
                <p>Inicio</p>
            </div>
		</main>

		<!-- Sección de noticias -->
		<section>
			<main class="container">
				<div class="submenu">
					<div>
						<img id="img-item" src="../../Public/Imagenes/SUBMENU.png" alt="Imagen">
					</div>
					<div id="Submenu--banner">
						<p>Submenu</p>
					</div>
					<div class="submenu-item subtitulos">
						<a href="#Titulo--Autor">Más vendidos</a>
					</div>
					<div class="submenu-item subtitulos">
						<a href="#Titulo--Editorial">Populares</a>
					</div>
				</div>
				<!-- Contenido principal -->
				<div class="main-content">
					<h1 id="Titulo--Inicio">Nuestra Página</h1>
					<p>Libre Praesto es tu librería de préstamos de libros favorita, dedicada a fomentar la lectura y el acceso a la cultura. Contamos con un extenso catálogo que abarca desde clásicos de la literatura hasta novedades editoriales, tanto en ficción como en no ficción. Nuestro objetivo es brindar un servicio accesible y amigable, ofreciendo a nuestros usuarios la posibilidad de explorar nuevos mundos a través de los libros. ¡Únete a nuestra comunidad y descubre la magia de la lectura con Libre Praesto!</p>
					<!-- Contenedor de noticias -->
                     			<!--AQUI VA LA GRAFICA-->
			<?php
// Código PHP para obtener los datos de la base de datos
require_once('../../Config/conexion.php');

$conexion = new connect();
$conn = $conexion->getConnection();

$sql = "
    SELECT 
        l.nombre, 
        SUM(v.cantidad) AS total_vendido
    FROM 
        libros l
    INNER JOIN 
        ventas v 
    ON 
        l.id_libro = v.libro_id
    GROUP BY 
        l.nombre
    ORDER BY 
        total_vendido DESC
    LIMIT 4
";

$result = $conn->query($sql);

if ($result) {
    $libros = [];
    while ($row = $result->fetch_assoc()) {
        $libros[] = [
            'nombre' => htmlspecialchars($row['nombre']),
            'total_vendido' => intval($row['total_vendido'])
        ];
    }

    // Pasar los datos de PHP a JavaScript
    echo '<script>
        var datosLibros = {
            labels: ' . json_encode(array_column($libros, 'nombre')) . ',
            datasets: [{
                label: "Total Vendido",
                data: ' . json_encode(array_column($libros, 'total_vendido')) . ',
                backgroundColor: ["rgba(255, 99, 132, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(255, 206, 86, 0.2)", "rgba(75, 192, 192, 0.2)"],
                borderColor: ["rgba(255, 99, 132, 1)", "rgba(54, 162, 235, 1)", "rgba(255, 206, 86, 1)", "rgba(75, 192, 192, 1)"],
                borderWidth: 1
            }]
        };

        document.addEventListener("DOMContentLoaded", function () {
            var ctx = document.getElementById("graficaLibros").getContext("2d");
            var myChart = new Chart(ctx, {
                type: "bar",
                data: datosLibros,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>';
} else {
    die("Error en la consulta: " . $conn->error);
}
?>


<div>
<canvas id="graficaLibros" width="50" height="10"></canvas>
</div>
				</div>
                
			</main>

			<!-- Sección de Autores -->
			<hr>
				<div class="section-title">
					<h2 id="Titulo--Autor">Libros Mas vendidos</h2>
				</div>
			<hr>
            <section class="display--grid inicio-espacio">
			<?php
require_once('../../Config/conexion.php');

$conexion = new connect();
$conn = $conexion->getConnection();

// Consulta para obtener los libros más vendidos
$sql = "
    SELECT 
        l.id_libro, 
        l.nombre, 
        l.precio, 
        l.imagen_ubicacion, 
        l.imagen_original,
        SUM(v.cantidad) AS total_vendido
    FROM 
        libros l
    INNER JOIN 
        ventas v 
    ON 
        l.id_libro = v.libro_id
    GROUP BY 
        l.id_libro, l.nombre, l.precio, l.imagen_ubicacion, l.imagen_original
    ORDER BY 
        total_vendido DESC
    LIMIT 4
"; // Selecciona los 4 libros más vendidos

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
        echo '<form method="post" action="inicio.php">';
        echo '<input type="hidden" name="libro_id" value="' . htmlspecialchars($row['id_libro']) . '">';
        echo '<button class="button_AgregarLibro" type="submit" name="agregar_carrito">Comprar</button>';
        echo '</form>';
        echo '</div>';
        echo '<p class="total-book">Total Vendido: ' . intval($row['total_vendido']) . '</p>';

        echo '<a href="libro.php?id=' . htmlspecialchars($row['id_libro']) . '" class="detalle-btn">Ver Detalles</a>';
        echo '</div>'; // Cierre del div libro
    }
} else {
    echo '<p>No hay libros más vendidos disponibles en este momento.</p>';
}
?>
            </section>
			<!-- Sección de categorias -->
			<hr>
				<div class="section-title">
					<h2 id="Titulo--Editorial">Libros Populares </h2>
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
                echo '<form method="post" action="inicio.php">';
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
    </div>
</section>
	
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