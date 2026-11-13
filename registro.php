<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Café Serrano</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .login-box { max-width: 400px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; }
        .login-box input { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
    </style>
</head>
<body style="background-color: #f4f1ea;">
    <nav>
        <h1>☕ Café Serrano</h1>
        <ul><li><a href="login.php">Volver al Login</a></li></ul>
    </nav>

    <div class="login-box">
        <h2>Crear una Cuenta</h2>
        <form id="form-registro">
            <input type="text" id="nombre" placeholder="Nombre completo" required>
            <input type="text" id="usuario" placeholder="Nombre de usuario (@ejemplo)" required>
            <input type="email" id="correo" placeholder="Correo electrónico" required>
            <input type="password" id="password" placeholder="Contraseña" required>
            <button type="submit" style="margin-top: 10px;">Registrarme</button>
        </form>
    </div>

    <script src="js/notificaciones.js"></script>
    <script>
        document.getElementById('form-registro').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData();
            formData.append('nombre', document.getElementById('nombre').value);
            formData.append('usuario', document.getElementById('usuario').value);
            formData.append('correo', document.getElementById('correo').value);
            formData.append('password', document.getElementById('password').value);

            fetch('php/registro_proceso.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if(data.trim() === 'success') {
                    mostrarNotificacion('¡Cuenta creada con éxito! Ya puedes iniciar sesión.', function() {
                        window.location.href = 'login.php';
                    });
                } else {
                    mostrarNotificacion(data); 
                }
            });
        });
    </script>
</body>
</html>