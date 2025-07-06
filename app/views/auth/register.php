<link rel="stylesheet" href="/petfriend/public/css/registro.css">

<div class="container vh-100">
    <div class="row justify-content-center align-items-center h-100 pt-4">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card-body">
                <div class="card-header text-center">
                    <h2>Registro</h2>
                </div>
                <form id="registerForm">
                    <input class="form-control mb-2" type="text" name="nombre" placeholder="Nombre" required>
                    <input class="form-control mb-2" type="text" name="apellido" placeholder="Apellido" required>
                    <input class="form-control mb-2" type="email" name="correo" placeholder="Correo" required>
                    <input class="form-control mb-2" type="number" name="edad" placeholder="Edad" required>
                    <input class="form-control mb-2" type="text" name="documento" placeholder="Número Documento" required>
                    <input class="form-control mb-2" type="text" name="ciudad" placeholder="Ciudad" required>
                    <input class="form-control mb-2" type="password" name="contrasena" placeholder="Contraseña" required>
                    <input id="confirmar" class="form-control mb-2" type="password" placeholder="Confirmar Contraseña" required>

                    <!-- Selector de rol -->
                    <select name="rol" class="form-control mb-2">
                        <option value="usuario" selected>Usuario</option>
                        <option value="admin">Administrador</option>
                    </select>

                    <!-- Código secreto solo si se elige admin -->
                    <input class="form-control mb-2" type="text" name="codigo_admin" placeholder="Código de administrador (solo si es admin)">

                    <button class="btn btn-primary w-100" type="submit">REGISTRARSE</button>
                </form>

                <div class="d-flex justify-content-between mt-2">
                    <p>¿Ya tienes cuenta?</p>
                    <a href="/petfriend/public/auth/login">Iniciar sesión</a>
                </div>

                <div id="mensaje" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>

<scrip src="/petfriend/public/js/register.js"></script>