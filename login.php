<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Café Serrano</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .login-box { max-width: 400px; margin: 80px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; }
        .login-box input { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
    </style>
</head>
<body style="background-color: #f4f1ea;">
    <nav>
        <h1>☕ Café Serrano</h1>
        <ul><li><a href="index.php">Volver al Inicio</a></li></ul>
    </nav>

    <div class="login-box">
        <h2>Iniciar Sesión</h2>
        <!-- Ya no enviamos el form a otro archivo, usamos Fetch para poder mostrar notificaciones -->
        <form id="form-login">
            <input type="email" id="correo" placeholder="Correo electrónico" required>
            <input type="password" id="password" placeholder="Contraseña" required>
            <button type="submit" style="margin-top: 10px;">Entrar</button>
        </form>
        
        <p style="margin-top: 15px; font-size: 14px;">
            ¿No tienes cuenta? <a href="registro.php" style="color: #d4a373; font-weight: bold;">Regístrate aquí</a>
        </p>
    </div>

    <script src="js/notificaciones.js"></script>
    <script>
        document.getElementById('form-login').addEventListener('submit', function(e) {
            e.preventDefault(); // Evita que la página se recargue

            let formData = new FormData();
            formData.append('correo', document.getElementById('correo').value);
            formData.append('password', document.getElementById('password').value);

            fetch('php/login_proceso.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if(data.trim() === 'success') {
                    mostrarNotificacion('¡Bienvenido de vuelta!', function() {
                        window.location.href = 'perfil.php';
                    });
                } else {
                    mostrarNotificacion(data); // Muestra el error que devuelve PHP
                }
            });
        });
    </script>
</body>
</html>