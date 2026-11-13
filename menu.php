<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú - Café Serrano</title>
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

    <main class="menu-container">
        <h2>Nuestro Menú</h2>
        
        <?php
        include 'php/conexion.php';
        
        // Consultamos las categorías únicas que existen en la BD
        $sql_categorias = "SELECT DISTINCT categoria FROM productos";
        $res_categorias = $conexion->query($sql_categorias);

        if ($res_categorias->num_rows > 0) {
            while($cat = $res_categorias->fetch_assoc()) {
                $categoria_actual = $cat['categoria'];
                echo "<h3 style='margin-top: 40px; color: #3e2723; border-bottom: 2px solid #d4a373; padding-bottom: 10px;'>$categoria_actual</h3>";
                echo "<div class='productos'>";
                
                // Traemos los productos de esta categoría
                $sql_prods = "SELECT * FROM productos WHERE categoria = '$categoria_actual'";
                $res_prods = $conexion->query($sql_prods);
                
                while($row = $res_prods->fetch_assoc()) {
                    echo "<div class='producto'>";
                    echo "<h3>" . $row["nombre"] . "</h3>";
                    echo "<p>" . $row["descripcion"] . "</p>";
                    echo "<p class='precio'>$" . $row["precio"] . "</p>";
                    echo "<button onclick=\"agregarAlCarrito('" . $row["nombre"] . "', " . $row["precio"] . ")\">Agregar</button>";
                    echo "</div>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No hay productos disponibles.</p>";
        }
        ?>
    </main>
    
    <script src="js/notificaciones.js"></script>
    <script src="js/carrito.js"></script>
</body>
</html>