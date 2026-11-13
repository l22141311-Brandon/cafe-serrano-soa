<?php
include 'php/sesion.php'; 
include 'php/conexion.php';

$nombre_usuario = $_SESSION['usuario_nombre'];
$id_usuario = $_SESSION['usuario_id'];

// Consultamos el saldo actual
$sql_user = "SELECT saldo FROM usuarios WHERE id = $id_usuario";
$res_user = $conexion->query($sql_user);
$datos_user = $res_user->fetch_assoc();
$saldo_actual = $datos_user['saldo'] ? $datos_user['saldo'] : 0.00;

// Consultamos los pedidos (ahora traemos el detalle)
$sql_pedidos = "SELECT * FROM pedidos WHERE cliente_nombre = '$nombre_usuario' ORDER BY fecha DESC LIMIT 5";
$resultado_pedidos = $conexion->query($sql_pedidos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Café Serrano</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .perfil-container { max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header-perfil { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .foto-perfil { width: 120px; height: 120px; border-radius: 50%; background-color: #d4a373; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; }
        
        .tarjeta-digital { background: linear-gradient(135deg, #3e2723, #d4a373); color: white; padding: 20px; border-radius: 15px; margin-bottom: 30px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .btn-recargar { background: white; color: #3e2723; border: none; padding: 10px 20px; border-radius: 25px; font-weight: bold; cursor: pointer; margin-top: 10px; transition: 0.2s; }
        
        .historial-pedidos { margin-top: 30px; }
        .pedido-item { border: 1px solid #eee; padding: 15px; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: flex-start; background: #fafafa; }
        
        .detalle-productos { font-size: 13px; color: #666; margin-top: 5px; line-height: 1.4; }
        
        .estado-badge { padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; color: white; display: inline-block; margin-bottom: 5px; }
        .badge-pagado { background-color: #d4a373; }
        .badge-preparacion { background-color: #f4a261; }
        .badge-listo { background-color: #2a9d8f; }
        
        .btn-qr { display: block; width: 100%; text-align: center; background-color: #3e2723; color: white; padding: 15px; border-radius: 10px; text-decoration: none; font-weight: bold; margin-top: 20px; font-size: 18px; }
    </style>
</head>
<body style="background-color: #f4f1ea;">
    <nav>
        <h1>☕ Café Serrano</h1>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="menu.php">Menú</a></li>
            <li><a href="carrito.php">🛒 Carrito</a></li>
            
            <?php if($_SESSION['usuario_rol'] == 'admin'): ?>
                <li><a href="admin_dashboard.php" style="color: #ffd700;">📊 Dashboard</a></li>
            <?php elseif($_SESSION['usuario_rol'] == 'barista'): ?>
                <li><a href="admin.php" style="color: #ffd700;">👨‍🍳 Comandas</a></li>
            <?php endif; ?>

            <li><a href="php/logout.php" style="color: #ff9999;">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <div class="perfil-container">
        <div class="header-perfil">
            <div class="foto-perfil" onclick="mostrarNotificacion('Función de subir foto en desarrollo')"><?php echo strtoupper(substr($nombre_usuario, 0, 1)); ?></div>
            <h2><?php echo $nombre_usuario; ?></h2>
            <p style="color: gray; margin: 5px 0;">Miembro desde: Hoy | Rol: <?php echo $_SESSION['usuario_rol']; ?></p>
            <span style="background: gold; color: #3e2723; padding: 3px 10px; border-radius: 15px; font-size: 12px; font-weight: bold;">⭐ Miembro Oro</span>
        </div>

        <div class="tarjeta-digital">
            <p style="margin: 0; font-size: 14px; opacity: 0.8;">Saldo Disponible</p>
            <h1 style="margin: 10px 0; font-size: 40px;">$<?php echo number_format($saldo_actual, 2); ?></h1>
            <p style="margin: 0; font-size: 14px;">⭐ Estrellas: 45 / 100</p>
            
            <form id="form-recarga" style="margin-top: 15px; display: flex; gap: 10px; justify-content: center;">
                <select id="monto-recarga" style="padding: 8px; border-radius: 5px; border: none; font-weight: bold;">
                    <option value="50">+ $50</option>
                    <option value="100">+ $100</option>
                    <option value="200">+ $200</option>
                </select>
                <button type="button" class="btn-recargar" onclick="recargarSaldo()" style="margin-top: 0;">Recargar</button>
            </form>
        </div>

        <a href="#" class="btn-qr" onclick="mostrarNotificacion('Este es tu código QR personal. ¡Muestra esta pantalla al barista!')">📱 Mostrar mi Código QR</a>

        <div class="historial-pedidos">
            <h3>Mis Pedidos Recientes</h3>
            <?php if ($resultado_pedidos && $resultado_pedidos->num_rows > 0): ?>
                <?php while($pedido = $resultado_pedidos->fetch_assoc()): 
                    $estado_texto = $pedido['estado'];
                    $clase_badge = 'badge-pagado';
                    $icono = '☕';
                    
                    if ($estado_texto == 'En preparación') {
                        $clase_badge = 'badge-preparacion';
                        $icono = '⏳';
                    } elseif ($estado_texto == 'Listo') {
                        $clase_badge = 'badge-listo';
                        $icono = '✅';
                    }
                ?>
                    <div class="pedido-item">
                        <div>
                            <strong>Pedido #<?php echo $pedido['id']; ?></strong><br>
                            <small style="color: gray;"><?php echo date('d/m/Y h:i A', strtotime($pedido['fecha'])); ?></small>
                            
                            <!-- Mostramos el detalle del pedido -->
                            <div class="detalle-productos">
                                <?php 
                                    // Limpiamos la coma final que se guardó
                                    echo isset($pedido['detalles']) ? rtrim($pedido['detalles'], ', ') : "Detalle no disponible"; 
                                ?>
                            </div>
                        </div>
                        <div style="text-align: right; min-width: 100px;">
                            <span class="estado-badge <?php echo $clase_badge; ?>"><?php echo $icono . ' ' . $estado_texto; ?></span><br>
                            <strong style="font-size: 18px;">$<?php echo $pedido['total']; ?></strong>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: gray; text-align: center; padding: 20px;">Aún no tienes pedidos registrados. ¡Pide tu primer café!</p>
            <?php endif; ?>
        </div>

        <h3 style="margin-top: 40px;">Ajustes de Cuenta</h3>
        <div class="seccion-ajustes" style="display: flex; flex-direction: column; gap: 10px;">
            <label>Nombre:</label>
            <input type="text" value="<?php echo $nombre_usuario; ?>" style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%; box-sizing: border-box;">
            <button onclick="mostrarNotificacion('Datos actualizados (simulación)')" style="background-color: #d4a373; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%;">Guardar Cambios</button>
        </div>
    </div>

    <script src="js/notificaciones.js"></script>
    <script>
        function recargarSaldo() {
            let monto = document.getElementById('monto-recarga').value;
            let formData = new FormData();
            formData.append('monto', monto);

            fetch('php/recargar_saldo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    mostrarNotificacion('¡Recarga exitosa! Se han añadido $' + monto + ' a tu monedero.', function() {
                        window.location.reload();
                    });
                } else {
                    mostrarNotificacion('Error al recargar el saldo.');
                }
            });
        }
    </script>
</body>
</html>