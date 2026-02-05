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
    header("Location: ../Vistas/Catalogo.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Catálogo - Libre Praesto</title>
		<link rel="stylesheet" type="text/css" href="../../Public/CSS/normalize.css">
		<link rel="stylesheet" type="text/css" href="../../Public/CSS/estilo.css">
    </head>

    <body>
    <!-- Sección del encabezado de la página -->    
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
            <h1 id="Titulo--Catalogo">CATÁLOGO</h1>
			<p id="Descripcion--Catalogo">
			Recorre nuestros estantes virtuales repletos de libros, cuidadosamente seleccionados para satisfacer las más diversas 
			preferencias. 
			<br>
			En Libre Praesto encontrarás el libro perfecto para cada ocasión y estado de ánimo.
			</p>

            <!-- Filtros -->
            <div id="catalogo-filtro">
            <form id="filtro-genero" method="get" action="Catalogo.php">
                <label for="categoria">Categoría:</label>
                <select name="categoria" id="categoria">
                    <option value="">Todas</option>
                    <option value="fantasia" <?php if (isset($_GET['categoria']) && $_GET['categoria'] == 'fantasia') echo 'selected'; ?>>Fantasía</option>
                    <option value="terror" <?php if (isset($_GET['categoria']) && $_GET['categoria'] == 'terror') echo 'selected'; ?>>Terror</option>
                    <option value="novela" <?php if (isset($_GET['categoria']) && $_GET['categoria'] == 'novela') echo 'selected'; ?>>Novela</option>
                    <option value="no-ficcion" <?php if (isset($_GET['categoria']) && $_GET['categoria'] == 'no-ficcion') echo 'selected'; ?>>No Ficción</option>
                </select>
                <button type="submit">Filtrar</button>
            </form>

            <form id="filtro-autor" method="get" action="Catalogo.php">
                <label for="autor">Autor:</label>
                <select name="autor" id="autor">
                    <option value="">Todas</option>
                    <option value="Gabriel García Márquez" <?php if (isset($_GET['autor']) && $_GET['autor'] == 'Gabriel García Márquez') echo 'selected'; ?>>Gabriel García Márquez</option>
                    <option value="Isabel Allende" <?php if (isset($_GET['autor']) && $_GET['autor'] == 'Isabel Allende') echo 'selected'; ?>>Isabel Allende</option>
                    <option value="Mario Vargas Llosa" <?php if (isset($_GET['autor']) && $_GET['autor'] == 'Mario Vargas Llosa') echo 'selected'; ?>>Mario Vargas Llosa</option>
                    <option value="Carlos Ruiz Zafón" <?php if (isset($_GET['autor']) && $_GET['autor'] == 'Carlos Ruiz Zafón') echo 'selected'; ?>>Carlos Ruiz Zafón</option>
                    <option value="Paulo Coelho" <?php if (isset($_GET['autor']) && $_GET['autor'] == 'Paulo Coelho') echo 'selected'; ?>>Paulo Coelho</option>
                </select>

                <button type="submit">Filtrar</button>
            </form>
            </div>
		</main>

		
		<!-- Sección de la cuadrícula de libros -->
		<section class="display--grid">
		<!-- Cada libro es un div con clase 'book' -->		
		<?php
include('../../Config/conexion.php');  // Ajusta la ruta según corresponda

$conexion = new connect();
$conn = $conexion->getConnection();

// Obtener los filtros
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$autor = isset($_GET['autor']) ? $_GET['autor'] : '';

// Construir la consulta con filtros
$sql = "SELECT * FROM libros WHERE 1";

// Filtrar por categoría
if ($categoria) {
    $sql .= " AND categoria = ?";
}

// Filtrar por autor
if ($autor) {
    $sql .= " AND autor LIKE ?";
}

$stmt = $conn->prepare($sql);

// Vincular parámetros si existen filtros
if ($categoria && $autor) {
    $stmt->bind_param('ss', $categoria, "%$autor%");
} elseif ($categoria) {
    $stmt->bind_param('s', $categoria);
} elseif ($autor) {
    $autor_busqueda = "%$autor%";  // Interpolar la cadena en una variable
    $stmt->bind_param('s', $autor_busqueda);  // Pasar la variable por referencia
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="book display_grid--row">';

        // Base URL de las imágenes
        $base_url = "http://localhost/LibreriaProyecto/Public/Imagenes/";
        $imagen_ubicacion = trim($row['imagen_ubicacion']);
        $imagen_original = trim($row['imagen_original']);
        $thumb_src = $base_url . $imagen_ubicacion . $imagen_original;

        // Mostrar la imagen del libro
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/LibreriaProyecto/Public/Imagenes/" . $imagen_ubicacion . $imagen_original)) {
            echo '<img class="Portada--catalogo" src="' . htmlspecialchars($thumb_src) . '" alt="Imagen del libro">';
        } else {
            echo '<p>No se encontró la imagen en la ubicación esperada.</p>';
        }

        // Mostrar detalles del libro
        echo '<p class="Libro--catalogo">' . htmlspecialchars($row['nombre']) . '</p>';
        echo '<p class="Libro--autor">' . htmlspecialchars($row['autor']) . '</p>';
        echo '<div class="book--precio">';
        echo '<p class="precio-libro">$' . number_format($row['precio'], 2) . '</p>';

        // Formulario para agregar al carrito
        echo '<form method="post" action="Catalogo.php">';
        echo '<input type="hidden" name="libro_id" value="' . htmlspecialchars($row['id_libro']) . '">';
        echo '<button type="submit" class="button_AgregarLibro">Comprar</button>';
        echo '</form>';
        echo '</div>';
        echo '<a href="libro.php?id=' . htmlspecialchars($row['id_libro']) . '" class="detalle-btn">Ver Detalles</a>';

        echo '</div>'; // Cierre del div libro
    }
} else {
    echo '<p>No se encontraron libros que coincidan con los filtros.</p>';
}
?>
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