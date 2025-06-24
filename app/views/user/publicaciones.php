  <h2 class="mb-4">Nueva Publicación de Adopción</h2>
  <form id="Formulario" enctype="multipart/form-data">
      <div class="mb-3">
          <label for="titulo" class="form-label">Título</label>
          <input type="text" name="titulo" id="titulo" class="form-control" required>
      </div>
      <div class="mb-3">
          <label for="contenido" class="form-label">Descripción</label>
          <textarea name="contenido" id="contenido" class="form-control" rows="5" required></textarea>
      </div>
      <div class="mb-3">
          <label for="imagen" class="form-label">Imagen de la mascota</label>
          <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
      </div>
      <button type="submit" class="btn btn-primary">Publicar</button>
      <a href="/petfriend/public/user" class="btn btn-secondary">Cancelar</a>
      <div id="mensaje" class="mt-2 text-muted"></div>
  </form>

  <script src="/petfriend/public/js/publicaciones.js"></script>