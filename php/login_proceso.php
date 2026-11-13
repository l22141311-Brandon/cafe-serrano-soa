<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        if ($password == $usuario['password']) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            
            echo "success"; // Mandamos esta palabra para que el JS sepa que entró
        } else {
            echo "Contraseña incorrecta. Intenta de nuevo.";
        }
    } else {
        echo "El correo no está registrado.";
    }
}
?>