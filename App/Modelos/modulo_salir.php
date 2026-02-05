<?php
//Destruye la sesión al salir el usuario
    session_start();
    session_destroy();
    header("Location: ../../Public/index.php");
    exit();
?>