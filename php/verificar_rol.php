<?php
session_start();

// Función para verificar si tiene el rol correcto
function verificarRol($rolesPermitidos) {
    if (!isset($_SESSION['usuario_rol'])) {
        // Si no hay sesión, lo mandamos al login
        header("Location: ../login.php");
        exit();
    }

    if (!in_array($_SESSION['usuario_rol'], $rolesPermitidos)) {
        // Si está logueado pero no tiene el rol, lo regresamos a su perfil
        header("Location: ../perfil.php");
        exit();
    }
}
?>