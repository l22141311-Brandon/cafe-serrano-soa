<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Café Serrano - Inicio</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <input type="hidden" id="nombre-usuario-oculto" value="<?php echo isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'invitado'; ?>">

    <nav>
        <h1>☕ Café Serrano</h1>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="menu.php">Menú</a></li>
            
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <li><a href="perfil.php">👤 Mi Perfil (<?php echo $_SESSION['usuario_nombre']; ?>)</a></li>
                
                <!-- BOTONES PARA ADMIN Y BARISTA -->
                <?php if($_SESSION['usuario_rol'] == 'admin'): ?>
                    <li><a href="admin_dashboard.php" style="color: #ffd700;">📊 Dashboard</a></li>
                <?php elseif($_SESSION['usuario_rol'] == 'barista'): ?>
                    <li><a href="admin.php" style="color: #ffd700;">👨‍🍳 Comandas</a></li>
                <?php endif; ?>

                <li><a href="php/logout.php" style="color: #ff9999;">Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="login.php">Iniciar Sesión</a></li>
            <?php endif; ?>

            <li><a href="carrito.php">🛒 Mi Carrito</a></li>
        </ul>
    </nav>

    <header class="hero">
        <h2>EL MEJOR CAFÉ DE LA SIERRA</h2>
        <p>Despierta tus sentidos con nuestro café de altura.</p>
        <a href="menu.php" class="btn-principal">Ver Menú / Ordenar Ahora</a>
    </header>

    <section class="favoritos">
        <h2>Nuestros Favoritos</h2>
        <div class="productos">
            <div class="producto">
                <h3>Capuchino</h3>
                <p>$45.00</p>
                <button onclick="agregarAlCarrito('Capuchino', 45)">Agregar al carrito</button>
            </div>
            <div class="producto">
                <h3>Americano</h3>
                <p>$35.00</p>
                <button onclick="agregarAlCarrito('Americano', 35)">Agregar al carrito</button>
            </div>
        </div>
    </section>
    
    <script src="js/notificaciones.js"></script>
    <script src="js/carrito.js"></script>
</body>
</html>