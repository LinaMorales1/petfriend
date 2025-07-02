<div class="container">

  <!-- 🔘 Navegación por pestañas -->
  <ul class="nav nav-tabs mb-4">
    <li class="nav-item">
      <a class="nav-link active" href="#" onclick="mostrarSeccion('perfil', event)">Perfil</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" onclick="mostrarSeccion('contrasena', event)">Contraseña</a>
    </li>
  </ul>

  <!-- ✅ Sección 1: Perfil -->
  <div id="perfil" class="seccion activa">
    <h4>Datos de Perfil</h4>

    <?php if (!empty($mensaje)): ?>
      <div class="alert alert-info">
        <?= htmlspecialchars($mensaje) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="/petfriend/public/user/actualizarPerfil">
      <input type="hidden" name="actualizar_perfil" value="1">

      <div class="mb-3">
        <label class="form-label">Nombres</label>
        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['NOMBRES']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Apellidos</label>
        <input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($usuario['APELLIDOS']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Ciudad</label>
        <input type="text" name="ciudad" class="form-control" value="<?= htmlspecialchars($usuario['CIUDAD']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Edad</label>
        <input type="number" name="edad" class="form-control" value="<?= htmlspecialchars($usuario['EDAD']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Correo</label>
        <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['CORREO']) ?>">
      </div>

      <button type="submit" class="btn btn-primary">Actualizar perfil</button>
    </form>
  </div>

  <!-- ✅ Sección 2: Contraseña -->
  <!-- ✅ Sección 2: Contraseña -->
<div id="contrasena" class="seccion" style="display: none;">
    <h4>Restablecer contraseña</h4>
    <?php if (!empty($mensajePassword)): ?>
  <div class="alert alert-info">
    <?= htmlspecialchars($mensajePassword) ?>
  </div>
<?php endif; ?>

    <form method="POST" action="/petfriend/public/user/cambiarContrasena">
      <input type="hidden" name="cambiar_password" value="1">

      <div class="mb-3">
        <label class="form-label">Nueva contraseña</label>
        <input type="password" name="nueva_password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" name="confirmar_password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-danger">Cambiar contraseña</button>

      <h5 class="mt-3">Recomendaciones:</h5>
      <ul>
        <li>8 caracteres mínimo</li>
        <li>Al menos 1 mayúscula</li>
        <li>Al menos 1 número</li>
      </ul>
    </form>
  </div>

</div>


