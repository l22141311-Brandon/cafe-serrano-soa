<?php
// La función session_status() revisa si ya hay una sesión activa antes de iniciarla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si no hay una sesión iniciada, lo patea al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}
?>