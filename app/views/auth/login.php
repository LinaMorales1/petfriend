<link rel="stylesheet" href="/petfriend/public/css/login.css">

<div class="logo">
    <img src="/petfriend/public/img/LOGOTIPO PET FRIEND.png" alt="LOGOTIPO" width="130px">
</div>

<div class="login-container">
    <h2>Iniciar sesión</h2>

    <form id="loginForm">
        <div>
            <label for="correo">Correo:</label>
            <input type="text" id="correo" name="correo" required />
        </div>
        <div>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required />
        </div>
        <button type="submit">Ingresar</button>
    </form>

    <div class="d-flex justify-content-between">
        <p>¿No tienes cuenta?</p>
        <a href="/petfriend/public/auth/register">REGISTRARSE</a>
    </div>

    <div id="mensaje" class="mt-2"></div>

    <script src="/petfriend/public/js/login.js"></script>
</div>