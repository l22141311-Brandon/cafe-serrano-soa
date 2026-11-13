<?php
include 'php/verificar_rol.php';
verificarRol(['admin']);

include 'php/conexion.php';

$sql_ventas = "SELECT SUM(total) as total_ventas, COUNT(id) as total_pedidos FROM pedidos";
$res_ventas = $conexion->query($sql_ventas);
$datos_ventas = $res_ventas->fetch_assoc();
$total_dinero = $datos_ventas['total_ventas'] ? $datos_ventas['total_ventas'] : 0;
$total_pedidos = $datos_ventas['total_pedidos'] ? $datos_ventas['total_pedidos'] : 0;

$sql_usuarios = "SELECT id, nombre, correo, rol, saldo FROM usuarios";
$res_usuarios = $conexion->query($sql_usuarios);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Café Serrano</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .dashboard-container { padding: 40px; max-width: 1000px; margin: 0 auto; }
        .metricas { display: flex; gap: 20px; margin-bottom: 30px; }
        .tarjeta-metrica { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); flex: 1; text-align: center; border-top: 5px solid #d4a373; }
        .tarjeta-metrica h3 { margin: 0; color: #666; font-size: 16px; }
        .tarjeta-metrica h1 { margin: 10px 0 0; color: #3e2723; font-size: 36px; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 40px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #3e2723; color: white; }
        .nav-admin { display: flex; gap: 20px; margin-bottom: 20px; }
        .nav-admin a { background: #3e2723; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .nav-admin a:hover { background: #d4a373; }
    </style>
</head>
<body style="background-color: #f4f1ea;">
    <nav>
        <h1>☕ Café Serrano - Dueño</h1>
        <ul>
            <li><a href="index.php">Ver Tienda</a></li>
            <li><a href="php/logout.php" style="color: #ff9999;">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <div class="nav-admin">
            <a href="admin.php">👨‍🍳 Ir al Panel del Barista</a>
            <a href="admin_productos.php">🍔 Gestionar Menú</a>
        </div>

        <h2>Resumen del Negocio</h2>
        <div class="metricas">
            <div class="tarjeta-metrica">
                <h3>Ventas Totales</h3>
                <h1>$<?php echo number_format($total_dinero, 2); ?></h1>
            </div>
            <div class="tarjeta-metrica">
                <h3>Pedidos Realizados</h3>
                <h1><?php echo $total_pedidos; ?></h1>
            </div>
            <div class="tarjeta-metrica">
                <h3>Usuarios Registrados</h3>
                <h1><?php echo $res_usuarios->num_rows; ?></h1>
            </div>
        </div>

        <h2>Directorio de Clientes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($res_usuarios && $res_usuarios->num_rows > 0) {
                    while($user = $res_usuarios->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>#" . $user["id"] . "</td>";
                        echo "<td>" . $user["nombre"] . "</td>";
                        echo "<td>" . $user["correo"] . "</td>";
                        echo "<td>" . $user["rol"] . "</td>";
                        echo "<td>$" . number_format($user["saldo"], 2) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>No hay usuarios registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>