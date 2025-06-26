<div class="container py-4">
  <h2 class="mb-4 text-center">📌 Publicaciones - Estado: <?= is_array($estado) ? implode(', ', $estado) : htmlspecialchars($estado) ?></h2>

  <?php if ($mensaje_exito): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Publicación eliminada correctamente.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  <?php endif; ?>

  <div class="d-flex justify-content-center gap-2 mb-4">
    <a href="?estado=EN%20CURSO" class="btn btn-success">🟢 En Curso</a>
    <a href="?estado=CANCELADA" class="btn btn-danger">🔴 Canceladas</a>
    <a href="?estado=COMPLETADA" class="btn btn-secondary">✅ Completadas</a>
  </div>

  <?php if (empty($publicaciones)): ?>
    <div class="alert alert-info">No hay publicaciones con el estado seleccionado.</div>
  <?php else: ?>
    <div class="row">
      <?php foreach ($publicaciones as $pub): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card shadow-sm">
            <?php if (!empty($pub['imagen'])): ?>
              <img src="../uploads/<?= htmlspecialchars($pub['imagen']) ?>" class="card-img-top" alt="Imagen">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($pub['titulo']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($pub['contenido']) ?></p>
              <form method="POST" action="/petfriend/public/user/actualizarEstado?estado=<?= urlencode(is_array($estado) ? ($estado[0] ?? '') : $estado) ?>" class="mt-3 d-flex gap-2">
                <input type="hidden" name="id" value="<?= $pub['id'] ?>">
                <select name="estado" class="form-select form-select-sm">
                  <option <?= $pub['estado'] == 'EN CURSO' ? 'selected' : '' ?>>EN CURSO</option>
                  <option <?= $pub['estado'] == 'CANCELADA' ? 'selected' : '' ?>>CANCELADA</option>
                  <option <?= $pub['estado'] == 'COMPLETADA' ? 'selected' : '' ?>>COMPLETADA</option>
                </select>
                <button type="submit" class="btn btn-outline-primary btn-sm">Actualizar</button>
              </form>
              <a href="/petfriend/public/users?delete=<?= $pub['id'] ?>&estado=<?= urlencode(is_array($estado) ? ($estado[0] ?? '') : $estado) ?>" class="btn btn-outline-danger btn-sm mt-2" onclick="return confirm('¿Eliminar esta publicación?')">🗑 Eliminar</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>


