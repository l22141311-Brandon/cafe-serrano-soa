<?php
include 'php/verificar_rol.php';
verificarRol(['admin']); // Solo el dueño puede gestionar el menú

include 'php/conexion.php';

// Lógica para AGREGAR un producto
if (isset($_POST['agregar_producto'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];

    $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria) VALUES ('$nombre', '$descripcion', '$precio', '$categoria')";
    $conexion->query($sql);
    header("Location: admin_productos.php?msg=agregado");
    exit();
}

// Lógica para ELIMINAR un producto
if (isset($_GET['eliminar'])) {
    $id_producto = $_GET['eliminar'];
    $conexion->query("DELETE FROM productos WHERE id = $id_producto");
    header("Location: admin_productos.php?msg=eliminado");
    exit();
}

// Consultamos todos los productos
$sql_productos = "SELECT * FROM productos ORDER BY id DESC";
$res_productos = $conexion->query($sql_productos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Menú - Café Serrano</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .admin-container { padding: 40px; max-width: 1000px; margin: 0 auto; }
        .form-producto { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 30px; display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end; }
        .form-producto input, .form-producto select { padding: 10px; border: 1px solid #ccc; border-radius: 5px; flex: 1; min-width: 150px; }
        .form-producto button { background: #2a9d8f; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; height: 40px; }
        
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #3e2723; color: white; }
        .btn-eliminar { background: #e63946; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body style="background-color: #f4f1ea;">
    <nav>
        <h1>☕ Café Serrano - Dueño</h1>
        <ul>
            <li><a href="admin_dashboard.php">📊 Dashboard</a></li>
            <li><a href="admin.php">👨‍🍳 Barista</a></li>
            <li><a href="admin_productos.php" style="color: #ffd700;">🍔 Menú</a></li>
            <li><a href="php/logout.php" style="color: #ff9999;">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <div class="admin-container">
        <h2>Gestión del Menú</h2>

        <!-- Formulario para agregar producto -->
        <form class="form-producto" method="POST" action="">
            <input type="text" name="nombre" placeholder="Nombre del producto" required>
            <input type="text" name="descripcion" placeholder="Descripción corta" required>
            <input type="number" step="0.01" name="precio" placeholder="Precio ($)" required>
            <select name="categoria" required>
                <option value="Bebidas Calientes">Bebidas Calientes</option>
                <option value="Bebidas Frías">Bebidas Frías</option>
                <option value="Panadería">Panadería</option>
            </select>
            <button type="submit" name="agregar_producto">+ Agregar Producto</button>
        </form>

        <!-- Tabla de productos -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($res_productos && $res_productos->num_rows > 0) {
                    while($prod = $res_productos->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>#" . $prod["id"] . "</td>";
                        echo "<td><strong>" . $prod["nombre"] . "</strong></td>";
                        echo "<td>" . $prod["descripcion"] . "</td>";
                        echo "<td>" . $prod["categoria"] . "</td>";
                        echo "<td>$" . $prod["precio"] . "</td>";
                        echo "<td><a href='admin_productos.php?eliminar=" . $prod["id"] . "' class='btn-eliminar' onclick='return confirm(\"¿Seguro que quieres eliminar este producto?\")'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>No hay productos en el menú.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="js/notificaciones.js"></script>
    <?php if(isset($_GET['msg'])): ?>
        <script>
            window.onload = function() {
                <?php if($_GET['msg'] == 'agregado'): ?>
                    mostrarNotificacion('¡Producto agregado al menú exitosamente!');
                <?php elseif($_GET['msg'] == 'eliminado'): ?>
                    mostrarNotificacion('Producto eliminado del menú.');
                <?php endif; ?>
            }
        </script>
    <?php endif; ?>
</body>
</html>