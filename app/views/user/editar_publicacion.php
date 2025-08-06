<h3>Editar publicación</h3>

<form action="/petfriend/public/user/actualizar_publicacion" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $publicacion['id'] ?>">

    <div class="mb-3">
        <label>Título</label>
        <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($publicacion['titulo']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Contenido</label>
        <textarea name="contenido" class="form-control" rows="4" required><?= htmlspecialchars($publicacion['contenido']) ?></textarea>
    </div>

    <div class="mb-3">
        <label>Imagen actual</label><br>
        <?php if (!empty($publicacion['imagen'])): ?>
            <img src="/petfriend/public/uploads/posts/<?= $publicacion['imagen'] ?>" style="max-width: 200px;"><br><br>
        <?php endif; ?>
        <input type="file" name="imagen">
    </div>

    <button type="submit" class="btn btn-success">💾 Guardar cambios</button>
</form>
