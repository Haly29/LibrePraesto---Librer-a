<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Soporte - Libre Praesto</title>
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
            <div class="Cuadrado_titutlo--banner">
                <p>Soporte</p>
            </div>
		</main>

		<section>     <!-- Sección de información sobre los integrantes -->
			<div class="integrante">
				<img class="foto" src="../../Public/Imagenes/Nosotros/AlexisLopez.jpeg" alt="Alexis López">
				<div class="info">
					<h2>Alexis López</h2>
					<p>Cédula: 8-994-1521</p>
					<p>Carrera: Lic. en Ingeniería de Software</p>
					<p>Tengo 21 años, la primera vez que tuve contacto con la programación fue con la aplicacion que se utilizaba en un grupo de robotica e informatica en el que estaba en la secundaria, además que los utilizabamos para programar robots de legos. Pasando al tema de herramientas web la única experiencia fue un laboratorio en globalización del software donde nos mandaron a crear una pagina de presentacion en HTML.</p>
				</div>
			</div>
			<hr>

			<div class="integrante reverse no-margin">
				<img class="foto" src="../../Public/Imagenes/Nosotros/Leonor.jpg" alt="Leonor Pérez">
				<div class="info2">
					<h2>Leonor Pérez</h2>
					<p>Cédula: 8-1004-2420</p>
					<p>Carrera: Lic. en Ingeniería de Software</p>
					<p>Tengo 20 años, comencé en el área de la programación cuando tenía 14 años. Con HTML, luego pasé a utilizar CSS y por último JavaScript, después aprendí Python y Django.</p>
				</div>
			</div>
			<hr>

			<div  class="integrante">
				<img class="foto" src="../../Public/Imagenes/Nosotros/Julissa.jpg" alt="Julissa Morales">
				<div class="info">
					<h2>Julissa Morales</h2>
					<p>Cédula: 8-995-2383</p>
					<p>Carrera: Lic. en Ingeniería de Software</p>
					<p>Tengo 22 años, desde que tengo 15 años me he adentrado en el mundo de la tecnologia, aprendi y me certifique en javascript.</p>
				</div>
			</div>
			<hr>
			
			<div class="integrante reverse no-margin">
				<img class="foto" src="../../Public/Imagenes/Nosotros/Alder.jpg" alt="Alder Somoza">
				<div class="info2">
					<h2>Alder Somoza</h2>
					<p>Cédula: 8-1002-814</p>
					<p>Carrera: Lic. en Ingeniería de Software</p>
					<p>Soy un joven de 20 años de edad, resido en el Corregimiento de Don Bosco, en el Distrito de Panamá. Mi experiencia como programador incluye la utilización de los lenguajes JAVA, C, Python, HTML y CSS.</p>
				</div>
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