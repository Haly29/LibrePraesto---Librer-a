<?php
ob_start();
session_start();

include_once '../Modelos/clase_cliente.php';    // Clase para gestionar usuarios
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
    if (!empty($_POST['usuario']) && !empty($_POST['contrasena'])) {
        $nombreUsuario = limpiarEntrada($_POST['usuario']);
        $clave = limpiarEntrada($_POST['contrasena']);
        
        try {
            $conexion = new connect(); // Usa la conexión principal
            $gestorUsuario = new Cliente($conexion);
            
            // Validar las credenciales del usuario
            if ($gestorUsuario->verificarCredenciales($nombreUsuario, $clave)) {
                redirigir('../Vistas/inicio.php'); // Redirige al inicio tras éxito
            } else {
                redirigir('../Vistas/login_cliente.php?error=login_invalido'); // Credenciales incorrectas
            }
        } catch (Exception $e) {
            error_log("Error en el login: " . $e->getMessage());
            redirigir('../Vistas/login_cliente.php?error=error_servidor');
        }
    } else {
        redirigir('../Vistas/login_cliente.php?error=campos_vacios');
    }
} else {
    echo "Método no permitido.";
}
?>