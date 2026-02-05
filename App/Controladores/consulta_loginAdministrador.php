<?php
ob_start();
session_start();

include_once '../Modelos/clase_usuario.php';    // Clase para gestionar usuarios
include_once '../../Config/conexion.php';        // Conexión principal a la base de datos
include_once '../../Config/conexion-login.php';

// Función para limpiar entrada del usuario
function limpiarEntrada($input) {
    return htmlspecialchars(trim($input));
}

// Redirige a una URL específica
function redirigir($url) {
    header("Location: $url");
    exit();
}

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Comprobar campos requeridos
    if (!empty($_POST['correo']) && !empty($_POST['contrasena'])) {
        $nombreUsuario = limpiarEntrada($_POST['correo']);
        $clave = limpiarEntrada($_POST['contrasena']);
        
        try {
            $conexion = new connect(); // Usa la conexión principal
            $gestorUsuario = new Usuario($conexion);
            
            // Validar las credenciales del usuario
            if ($gestorUsuario->verificarCredenciales($nombreUsuario, $clave)) {
                redirigir('../Vistas/MenuAdmin.php'); // Redirige al inicio tras éxito
            } else {
                redirigir('../Vistas/login_administrador.php?error=login_invalido'); // Credenciales incorrectas
            }
        } catch (Exception $e) {
            error_log("Error en el login: " . $e->getMessage());
            redirigir('../Vistas/login_administrador.php?error=error_servidor');
        }
    } else {
        redirigir('../Vistas/login_administrador.php?error=campos_vacios');
    }
} else {
    echo "Método no permitido.";
}
?>