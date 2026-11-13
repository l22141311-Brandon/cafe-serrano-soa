<?php
include 'php/verificar_rol.php';
verificarRol(['admin', 'barista']); // Admin y Barista pueden entrar

include 'php/conexion.php';

// Lógica para cambiar el estado
if (isset($_GET['cambiar_estado']) && isset($_GET['id'])) {
    $id_pedido = $_GET['id'];
    $nuevo_estado = $_GET['cambiar_estado'];
    $conexion->query("UPDATE pedidos SET estado = '$nuevo_estado' WHERE id = $id_pedido");
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Barista - Café Serrano</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .admin-container { padding: 40px; max-width: 1000px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #3e2723; color: white; }
        .badge { padding: 5px 10px; border-radius: 15px; color: white; font-size: 12px; font-weight: bold; }
        .bg-pagado { background-color: #d4a373; }
        .bg-preparacion { background-color: #f4a261; }
        .bg-listo { background-color: #2a9d8f; }
        .btn-accion { padding: 5px 10px; text-decoration: none; border-radius: 5px; color: white; font-size: 12px; display: inline-block; width: auto; }
        .btn-preparar { background-color: #f4a261; }
        .btn-listo { background-color: #2a9d8f; }
    </style>
</head>
<body style="background-color: #f4f1ea;">
    <nav>
        <h1>☕ Café Serrano - Barista</h1>
        <ul>
            <?php if($_SESSION['usuario_rol'] == 'admin'): ?>
                <li><a href="admin_dashboard.php">📊 Dashboard Dueño</a></li>
            <?php endif; ?>
            <li><a href="index.php">Ver Página Pública</a></li>
            <li><a href="php/logout.php" style="color: #ff9999;">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <div class="admin-container">
        <h2>Comandas de Pedidos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Detalle</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM pedidos ORDER BY fecha DESC";
                $resultado = $conexion->query($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    while($row = $resultado->fetch_assoc()) {
                        $estado = $row["estado"];
                        $badge_class = "bg-pagado";
                        if ($estado == "En preparación") $badge_class = "bg-preparacion";
                        if ($estado == "Listo") $badge_class = "bg-listo";

                        echo "<tr>";
                        echo "<td>#" . $row["id"] . "</td>";
                        echo "<td>" . (isset($row["cliente_nombre"]) ? $row["cliente_nombre"] : "Cliente") . "</td>";
                        echo "<td>" . (isset($row["detalles"]) ? $row["detalles"] : "Ver en sistema") . "</td>";
                        echo "<td>$" . $row["total"] . "</td>";
                        echo "<td><span class='badge $badge_class'>" . $estado . "</span></td>";
                        
                        echo "<td>";
                        if ($estado == "Pagado") {
                            echo "<a href='admin.php?cambiar_estado=En preparación&id=" . $row["id"] . "' class='btn-accion btn-preparar'>Preparar</a>";
                        } elseif ($estado == "En preparación") {
                            echo "<a href='admin.php?cambiar_estado=Listo&id=" . $row["id"] . "' class='btn-accion btn-listo'>Marcar Listo</a>";
                        } else {
                            echo "✔ Completado";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>No hay pedidos registrados aún.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>