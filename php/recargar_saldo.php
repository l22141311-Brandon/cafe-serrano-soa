<?php
session_start();
include 'conexion.php';

if (isset($_POST['monto']) && isset($_SESSION['usuario_id'])) {
    $monto = floatval($_POST['monto']);
    $id_usuario = $_SESSION['usuario_id'];

    // Sumamos el monto al saldo actual
    $sql = "UPDATE usuarios SET saldo = saldo + $monto WHERE id = $id_usuario";
    
    if ($conexion->query($sql) === TRUE) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
} else {
    echo json_encode(["success" => false]);
}
?>