<?php 
include 'php/sesion.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito - Café Serrano</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body style="background-color: #f4f1ea;">
    <input type="hidden" id="nombre-usuario-oculto" value="<?php echo isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'invitado'; ?>">

    <nav>
        <h1>☕ Café Serrano</h1>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="menu.php">Menú</a></li>
            <li><a href="perfil.php">👤 Mi Perfil</a></li>
            <li><a href="php/logout.php" style="color: #ff9999;">Cerrar Sesión</a></li>
            <li><a href="carrito.php">🛒 Mi Carrito (<span id="contador-carrito">0</span>)</a></li>
        </ul>
    </nav>

    <main class="menu-container">
        <h2>Tu Pedido (Mi Bolsa)</h2>
        <div id="lista-carrito" class="lista-carrito" style="margin: 20px 0;">
            <!-- Aquí JavaScript inyectará los productos -->
        </div>
        
        <div class="resumen-pago" style="background: white; padding: 20px; border-radius: 8px; max-width: 400px; margin: 0 auto; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <h3>Total a pagar: <span id="total-pagar">$0.00</span></h3>
            <label for="metodo-pago">Método de pago:</label>
            <select id="metodo-pago" style="padding: 8px; margin: 10px 0; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                <option value="Tarjeta">Tarjeta de Crédito/Débito</option>
                <option value="Efectivo">Efectivo en tienda</option>
            </select>
            <br><br>
            <button onclick="procesarPago()" class="btn-pagar">Pagar y Confirmar Pedido</button>
        </div>
    </main>

    <!-- Incluimos notificaciones primero, luego el carrito -->
    <script src="js/notificaciones.js"></script>
    <script src="js/carrito.js"></script>
</body>
</html>