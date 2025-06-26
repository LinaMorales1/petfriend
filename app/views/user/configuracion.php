<div class="row">

  <!-- Formulario de Perfil -->
  <div class="col-md-7">
    <h4>Datos de Perfil</h4>
    <form method="POST">
      <!-- Este campo oculto se usa para identificar que se está enviando el formulario de perfil -->
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

  <!-- Formulario de Contraseña -->
  <div class="col-md-5">
    <h4>Restablecer contraseña</h4>
    <form method="POST">
      <!-- Este campo oculto se usa para identificar que se está enviando el formulario de cambio de contraseña -->
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

      <!-- Reglas para guiar al usuario -->
      <h4 class="mt-4">PARA CAMBIAR TU CONTRASEÑA</h4>
      <ul class="password-rules">
        <li>Minimo 8 caracteres</li>
        <li>Debe tener una mayúscula</li>
        <li>Debe tener mínimo 1 número</li>
        <li>Puede tener caracteres especiales</li>
      </ul>
    </form>
  </div>

</div>
