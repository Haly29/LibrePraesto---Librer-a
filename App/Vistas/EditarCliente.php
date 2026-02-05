<?php
session_start();
include_once '../../Config/clase_encriptacion.php'; 
include_once '../../Config/conexion.php';
include_once '../Modelos/clase_cliente.php';

if (isset($_GET['id_cliente']) && !empty($_GET['id_cliente'])) {
    $idCliente = $_GET['id_cliente'];
} else {
    echo "Error: No se proporcionó un ID de cliente.";
    exit();
}

$database = new connect();
$cusuario = new Cliente($database);
$row = $cusuario->getClienteData($idCliente);

if (!$row) {
    echo "Error: Cliente no encontrado o no se pudo recuperar la información.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula'];
    $primerNombre = $_POST['nombre'];
    $segundoNombre = $_POST['segundoNombre'];
    $primerApellido = $_POST['apellido'];
    $segundoApellido = $_POST['segundoApellido'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $sucursal = $_POST['sucursal'];

    $cusuario->updateUserData($idCliente, $cedula, $primerNombre, $segundoNombre, $primerApellido, $segundoApellido, $fechaNacimiento, $sucursal);

    if (!empty($_POST['password']) && $_POST['password'] === $_POST['confirm_password']) {
        $newPassword = $_POST['password'];
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $cusuario->updateUserPassword($idCliente, $hashedPassword);
    }

    header("Location: ../Vistas/ModuloRegistroCliente.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" type="text/css" href="../../Public/CSS/normalize.css">
    <link rel="stylesheet" type="text/css" href="../../Public/CSS/estilo.css">
</head>
<body id="Background">
    <section class="registro flex flex--row flex--center">
        <article id="cuadrado--cliente">
        <h1 id="Subtítulo">Editar Datos de Usuario</h1>
        
        <form class="flex flex--column" method="POST">
            <div class="input-row">
                <label class="input-label-cliente" for="cedula">Cedula:</label>
                <input class="input-Form" type="text" name="cedula" id="cedula" value="<?php echo isset($row['cip']) ? htmlspecialchars($row['cip']) : ''; ?>" required>
            </div>

            <div class="input-row">
                <label class="input-label-cliente" for="nombre">Primer Nombre:</label>
                <input class="input-Form" type="text" name="nombre" id="nombre" value="<?php echo isset($row['primer_nombre']) ? htmlspecialchars($row['primer_nombre']) : ''; ?>" required>
            </div>

            <div class="input-row">
                <label class="input-label-cliente" for="segundoNombre">Segundo Nombre:</label>
                <input class="input-Form" type="text" name="segundoNombre" id="segundoNombre" value="<?php echo isset($row['segundo_nombre']) ? htmlspecialchars($row['segundo_nombre']) : ''; ?>" required>
            </div>

            <div class="input-row">
                <label class="input-label-cliente" for="apellido">Primer Apellido:</label>
                <input class="input-Form" type="text" name="apellido" id="apellido" value="<?php echo isset($row['primer_apellido']) ? htmlspecialchars($row['primer_apellido']) : ''; ?>" required>
            </div>

            <div class="input-row">
                <label class="input-label-cliente" for="segundoApellido">Segundo Apellido:</label>
                <input class="input-Form" type="text" name="segundoApellido" id="segundoApellido" value="<?php echo isset($row['segundo_apellido']) ? htmlspecialchars($row['segundo_apellido']) : ''; ?>" required>
            </div>

            <div class="input-row">
                <label class="input-label-cliente" for="fechaNacimiento">Fecha de Nacimiento:</label>
                <input class="input-Form" type="date" name="fechaNacimiento" id="fechaNacimiento" value="<?php echo isset($row['fecha_nacimiento']) ? htmlspecialchars($row['fecha_nacimiento']) : ''; ?>" required>
            </div>

            <div class="input-row">
                <label class="input-label-cliente" for="sucursal">Sucursal:</label>
                <select name="sucursal" id="sucursal">
                    <option value="1" <?php echo isset($row['sucursal_id']) && $row['sucursal_id'] == 1 ? 'selected' : ''; ?>>Central</option>
                    <option value="2" <?php echo isset($row['sucursal_id']) && $row['sucursal_id'] == 2 ? 'selected' : ''; ?>>Este</option>
                </select>
            </div>

            <div class="input-row">
                <label class="input-label-cliente" for="password">Nueva Contraseña:</label>
                <input class="input-Form" type="password" name="password" id="password">
            </div>

            <div class="input-row">
                <label class="input-label" for="confirm_password">Confirmar Contraseña:</label>
                <input class="input-Form" type="password" name="confirm_password" id="confirm_password">
            </div>

            <button id="buttons" type="submit">Actualizar Datos</button>
        </form>
        </article>
    </section>
</body>
</html>
