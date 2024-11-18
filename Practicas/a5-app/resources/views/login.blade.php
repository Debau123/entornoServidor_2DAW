<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>

    @if($errors->any())
        <div class="error-message">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="submit-btn">Iniciar Sesión</button>
    </form>

    <p class="footer-text">
        ¿No tienes una cuenta? <a href="/register">Regístrate aquí</a>
    </p>
</div>

</body>
</html>
