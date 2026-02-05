<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Iniciar Sesión - Libre Praesto</title>
        <link rel="stylesheet" type="text/css" href="../../Public/CSS/normalize.css">
        <link rel="stylesheet" type="text/css" href="../../Public/CSS/estilo.css">
    </head>

    <body id="Background">
        <!-- Area del logo -->    
        <section class="loginCliente flex flex--row flex--center">
            <div class="cuadrado_logo-Login cuadrado_logo--login">
                <img id="logo" src="../../Public/Imagenes/Logo cuadrado.png" alt="Logo de Libre Praesto">
            </div>
            <article id="cuadrado-Login">         <!-- Campo de llenado del login -->
                <p id="Subtítulo-Login">¡Inicia sesión para continuar!</p>
                <?php
                    if (isset($_GET['error'])) {
                        echo "<p>Usuario o contraseña incorrectos</p>";
                    }
                ?>
                <form action="../Controladores/consulta_loginCliente.php" method="post" class="flex flex--column">
                    <div class="input-container" data-tipo="usuario">
                        <input class="input" type="text" id="correo" name="usuario" placeholder="Usuario" required>
                    </div>
                    <div class="input-container" data-tipo="Password">
                        <input class="input" type="password" id="password" name="contrasena" placeholder="Contraseña" required>
                    </div>
                    <button id="buttons" type="submit">Iniciar Sesión</button>
                </form>
                <div id="leyenda">
                    <a id="link" href="../Vistas/registro_cliente.php">Regístrate</a>
                </div>
            </article>
        </section>
    </body>
</html>