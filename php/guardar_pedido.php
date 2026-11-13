<?php
session_start();
include 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data && isset($_SESSION['usuario_id'])) {
    $total = floatval($data['total']);
    $cliente = $_SESSION['usuario_nombre'];
    $id_usuario = $_SESSION['usuario_id'];
    
    // 1. Consultar el saldo actual del usuario
    $sql_saldo = "SELECT saldo FROM usuarios WHERE id = $id_usuario";
    $res_saldo = $conexion->query($sql_saldo);
    $usuario = $res_saldo->fetch_assoc();
    $saldo_actual = floatval($usuario['saldo']);

    // 2. Verificar si tiene saldo suficiente
    if ($saldo_actual >= $total) {
        // Cobrar: Restar el total del saldo
        $nuevo_saldo = $saldo_actual - $total;
        $conexion->query("UPDATE usuarios SET saldo = $nuevo_saldo WHERE id = $id_usuario");

        // Guardar el pedido
        $detalles = "";
        foreach($data['productos'] as $prod) {
            $detalles .= $prod['nombre'] . " (x1), ";
        }
        
        $sql_pedido = "INSERT INTO pedidos (cliente_nombre, total, estado, detalles) VALUES ('$cliente', '$total', 'Pagado', '$detalles')";
        
        if ($conexion->query($sql_pedido) === TRUE) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "mensaje" => "Error en pedido"]);
        }
    } else {
        // No tiene dinero suficiente
        echo json_encode(["success" => false, "mensaje" => "Saldo insuficiente"]);
    }
} else {
    echo json_encode(["success" => false, "mensaje" => "No hay datos o no está logueado"]);
}
?>