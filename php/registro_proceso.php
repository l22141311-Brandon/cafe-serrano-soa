<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    // Verificar si el correo ya existe
    $check = $conexion->query("SELECT id FROM usuarios WHERE correo = '$correo'");
    if ($check->num_rows > 0) {
        echo "Este correo ya está registrado.";
        exit();
    }

    $sql = "INSERT INTO usuarios (nombre, usuario, correo, password) VALUES ('$nombre', '$usuario', '$correo', '$password')";
    
    if ($conexion->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "Error al crear la cuenta. Intenta de nuevo.";
    }
}
?>