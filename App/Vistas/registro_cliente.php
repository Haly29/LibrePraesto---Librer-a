<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Registro - Libre Praesto</title>
        <link rel="stylesheet" type="text/css" href="../../Public/CSS/normalize.css">
        <link rel="stylesheet" type="text/css" href="../../Public/CSS/estilo.css">
    </head>

    <body id="Background">    <!-- Formulario para el registro de usuarios -->
        <section class="registro flex flex--row flex--center">
            <div class="cuadrado_logo cuadrado_logo--registro">
                <img id="logo" src="../../Public/Imagenes/Logo cuadrado.png" alt="Logo de Libre Praesto">
            </div>
            <article id="cuadrado">
                <p id="Subtítulo">¡Regístrate y descubre un mundo sin límites!</p>
                    <form action="../Controladores/consulta_registroCliente.php" method="post" class="flex flex--column"> 
        				<!-- Campo del primer nombre -->
                        <div class="input-row" data-tipo="Nombre">
                            <div>
                                <label class="input-label" for="PrimerNom">Primer nombre:</label>
                                <input class="input-Form" type="text" id="nombre" name="PrimerNom" placeholder="Primer nombre" required>     
                            </div>                   
                            <!-- Campo del segundo nombre -->
                            <div>
                                <label class="input-label" for="SegundoNom">Segundo nombre:</label>
                                <input class="input-Form" type="text" id="nombre" name="SegundoNom" placeholder="Segundo nombre" required>
                            </div>
                        </div>

        				<!-- Campo del primer apellido -->
                        <div class="input-row" data-tipo="Apellido">
                            <div>
                                <label class="input-label" for="PrimerApellido">Primer apellido:</label>
                                <input class="input-Form" type="text" id="apellido" name="PrimerApellido" placeholder="Primer apellido" required>
                            </div>
                            <div>
                                <label class="input-label" for="SegundoApellido">Segundo apellido:</label>
                                <input class="input-Form" type="text" id="apellido" name="SegundoApellido" placeholder="Segundo apellido" required>
                            </div>
                        </div>

                        <!-- Campo del cédula-->
                        <div class="input-row" data-tipo="Cedula">
                            <div>
                                <label class="input-label" for="cedula">Cédula:</label>
                                <input class="input-Form" type="text" id="cedula" name="cedula" placeholder="Cédula" required>
                            </div>
                            <!-- Campo de la Fecha de nacimiento -->
                            <div>
                                <label class="input-label" for="SegundoNom">Fecha de nacimiento:</label>
                                <input class="input-Form" type="date" id="FechaNacimiento" name="FechaNacimiento" placeholder="Fecha de nacimiento" required>
                            </div>
                        </div>

                        <!-- Campo del usuario-->
                        <div class="input-row" data-tipo="Usuario">
                            <div>
                                <label class="input-label" for="usuario">Usuario:</label>
                                <input class="input-Form" type="text" id="usuario" name="usuario" placeholder="Usuario" required>
                            </div>
                            <div>
                                <label class="input-label" for="correo">Correo:</label>
                                <input class="input-Form" type="text" id="correo" name="correo" placeholder="Correo" required>
                            </div>
                        </div>

        				<!-- Campo de la contraseña -->
                        <div class="input-row" data-tipo="Contraseña">
                            <div>
                                <label class="input-label" for="contrasena">Contraseña:</label>
                                <input class="input-Form" type="password" id="contraseña" name="contrasena" placeholder="Contraseña" required>
                            </div>
                            <!-- Campo de la contraseña -->
                            <div>
                                <label class="input-label" for="contrasea">Confirmar contraseña:</label>
                                <input class="input-Form" type="password" id="contraseña" name="contrasea" placeholder="Confirmar contraseña" required>
                            </div>
                        </div>

                        <!-- Campo de la sucursal-->
                        <div class="input-container-Form" data-tipo="Sucursal">
                            <label class="input-label" for="sucursal">Sucursal:</label>
                            <input class="input-Form" type="text" id="sucursal" name="sucursal" placeholder="Sucursal" required>
                        </div>
                        
                        <button id="buttons" type="submit">Registrarse</button>
                    </form>
                    <div id="leyenda">
                        <p>¿Ya tienes una cuenta?</p>
                        <a id="link" href="login_cliente.php">Iniciar Sesión</a>
                    </div>
            </article>
        </section>
    </body>
</html>