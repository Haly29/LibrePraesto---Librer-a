<?php
// Incluir la clase de conexión
include_once '../../Config/conexion.php';
include_once '../Modelos/clase_sanitizar.php';

try {
    // Limpiar y validar datos del formulario
    $cip = ($_POST['cedula']);
    if (!$cip) {
        die("La cédula no es válida.");
    }

    $primerNombre = Sanitize::cleanString($_POST['PrimerNom']);
    $segundoNombre = Sanitize::cleanString($_POST['SegundoNom']);
    $primerApellido = Sanitize::cleanString($_POST['PrimerApellido']);
    $segundoApellido = Sanitize::cleanString($_POST['SegundoApellido']);
    
    $fechaNacimiento = $_POST['FechaNacimiento'];
    if (!Sanitize::validateDate($fechaNacimiento)) {
        die("La fecha de nacimiento no es válida.");
    }

    $usuario = Sanitize::cleanString($_POST['usuario']);
    $correo = Sanitize::validateEmail($_POST['correo']);
    if (!$correo) {
        die("El correo proporcionado no es válido.");
    }

    $contrasena = $_POST['contrasena'];
    if (!Sanitize::validatePassword($contrasena)) {
        die("La contraseña debe tener al menos 4 caracteres.");
    }

    $confirmarContrasena = $_POST['contrasea'];
    if ($contrasena !== $confirmarContrasena) {
        die("Las contraseñas no coinciden.");
    }

    // Encriptar la contraseña
    $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Conexión a la base de datos
    $db = new connect();
    $query = "INSERT INTO clientes 
        (cip, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, fecha_nacimiento, usuario, correo, contraseña)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Ejecutar la consulta con parámetros usando una consulta preparada
    $db->executePreparedStatement($query, "sssssssss", $cip, $primerNombre, $segundoNombre, $primerApellido, $segundoApellido, $fechaNacimiento, $usuario, $correo, $contrasenaHash);

    // Redirigir a index.php después de un registro exitoso
    header("Location: ../../Public/index.php");
    exit;

} catch (Exception $e) {
    echo "Error en el registro: " . $e->getMessage();
}
?>