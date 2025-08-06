<!-- Hoja de estilos para el formulario de registro -->
<link rel="stylesheet" href="/petfriend/public/css/registro.css">

<!-- Contenedor principal de Bootstrap -->
<div class="container vh-100">
    <div class="row justify-content-center align-items-center h-100 pt-4">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">

            <div class="card-body">
                <!-- Título del formulario -->
                <div class="card-header text-center">
                    <h2>Registro</h2>
                </div>

                <!-- Formulario de registro de usuario -->
                <form id="registerForm" method="POST">
                    <!-- Campos de entrada del formulario con validación HTML5 (`required`) -->

                    <input class="form-control mb-2" type="text" name="nombre" placeholder="Nombre" required>

                    <input class="form-control mb-2" type="text" name="apellido" placeholder="Apellido" required>

                    <input class="form-control mb-2" type="email" name="correo" placeholder="Correo" required>

                    <input class="form-control mb-2" type="number" name="edad" placeholder="Edad" required>

                    <input class="form-control mb-2" type="text" name="documento" placeholder="Número Documento" required>

                    <input class="form-control mb-2" type="text" name="ciudad" placeholder="Ciudad" required>

                    <input class="form-control mb-2" type="text" name="celular" placeholder="Celular" required>

                    <input class="form-control mb-2" type="password" name="contrasena" placeholder="Contraseña" required>

                    <!-- Confirmación de contraseña (solo para validar en el frontend con JS) -->
                    <input id="confirmar" class="form-control mb-2" type="password" placeholder="Confirmar Contraseña" required>

                    <!-- Selección de rol (usuario o admin) -->
                    <select name="rol" class="form-control mb-2">
                        <option value="usuario" selected>Usuario</option>
                        <option value="admin">Administrador</option>
                    </select>

                    <!-- Campo opcional para ingresar código de administrador (solo si se elige rol admin) -->
                    <input class="form-control mb-2" type="text" name="codigo_admin" placeholder="Código de administrador (solo si es admin)">

                    <!-- Botón de envío del formulario -->
                    <button class="btn btn-primary w-100" type="submit">REGISTRARSE</button>
                </form>

                <!-- Enlace para usuarios ya registrados -->
                <div class="d-flex justify-content-between mt-2">
                    <p>¿Ya tienes cuenta?</p>
                    <a href="/petfriend/public/auth/login">Iniciar sesión</a>
                </div>

                <!-- Aquí se mostrará el mensaje de éxito o error (vía AJAX) -->
                <div id="mensaje" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>

<!-- Script con la lógica AJAX del formulario -->
<script src="/petfriend/public/js/register.js"></script>
