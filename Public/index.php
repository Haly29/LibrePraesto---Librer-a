<?php
// Verificar si se ha enviado el formulario y redirigir
if (isset($_POST['login_type'])) {
    if ($_POST['login_type'] == 'admin') {
        header("Location: ../App/Vistas/login_administrador.php"); // Redirigir al inicio de sesión de administrador
        exit();
    } else if ($_POST['login_type'] == 'cliente') {
        header("Location: ../App/Vistas/login_cliente.php"); // Redirigir al inicio de sesión de cliente
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libre Praesto</title>
    <link rel="stylesheet" type="text/css" href="../Public/CSS/normalize.css">
    <link rel="stylesheet" type="text/css" href="../Public/CSS/estilo.css">
</head>

    <header>
        <div class="logo_menu">
			<img src="../Public/Imagenes/Logo.png" alt="Libre Praesto Logo">
		</div>
        <nav>
            <ul>
                <li class="nav--index"><a href="index.php">Inicio</a></li>
                <li class="nav--index"><a href="../App/Vistas/registro_cliente.php">Registrarse</a></li>
            
                <li>
                    <form id="form--index" method="POST">
                        <select class="select--index" name="login_type" required>
                            <option value="">Cuenta</option>
                            <option value="admin">Administrador</option>
                            <option value="cliente">Cliente</option>
                        </select>
                        <button id="button--select" type="submit">Iniciar sesión</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

<body>
        <section class="stats">
            <article id="stats-container">
                <h1 id="Titulo-index">DONDE CADA LIBRO ES UNA NUEVA AVENTURA</h1>  
                
                <div class="stat-item">
                    <div>          
                        <div class="stat-content">
                            <img class="icons" src="../Public/Imagenes/icon-megusta.png" alt="Icono de me gusta">
                            <p class="parrafo-index"><strong>100%</strong><br>Satisfacción</p>
                        </div>
                    </div>

                    <div>
                        <div class="stat-content">
                            <img class="icons" src="../Public/Imagenes/icon-user.png" alt="Icono de usuario">
                            <p class="parrafo-index"><strong>250+</strong><br>Libros</p>
                        </div>
                    </div>
                </div>
            </article>  
            <img id="imagen-index" src="../Public/Imagenes/downl.jpg" alt="Imagen de un libro y una ardilla">
        </section>

    
    <footer>
        <div class="logo_footer">
			<img src="../Public/Imagenes/Logo.png" alt="Libre Praesto Logo">
		</div>
        <nav id="PiePag">
            <ul id="Submenu">
                <li class="LinkSubmenu"><a href="index.php">Inicio</a></li>
                <li class="LinkSubmenu"><a href="../App/Vistas/registro_cliente.php">Registrarse</a></li>
            </ul>
        </nav>
		<p id="copyright">© 2024 Libre Praesto. Todos los derechos reservados.</p>
    </footer>
</body>
</html>