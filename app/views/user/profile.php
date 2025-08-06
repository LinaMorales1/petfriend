<?php
$foto = !empty($usuario['FOTO'])
    ? "/petfriend/public/uploads/perfiles/" . $usuario['FOTO']
    : "/petfriend/public/img/default.jpg";
?>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <div class="row align-items-center text-center text-md-start">
            <!-- Foto de perfil -->
            <div class="col-12 col-md-3 mb-3 mb-md-0 d-flex justify-content-center">
                <img id="fotoPerfil" src="<?= $foto ?>" alt="Foto de perfil"
                    class="rounded-circle border shadow-sm"
                    style="width: 120px; height: 120px; object-fit: cover;">
            </div>

            <!-- Info usuario + formulario -->
            <div class="col-12 col-md-9">
                <h4 class="fw-bold mb-2"><?= htmlspecialchars($usuario['NOMBRES'] . ' ' . $usuario['APELLIDOS']) ?></h4>
                <p class="mb-1"><strong>Correo:</strong> <?= htmlspecialchars($usuario['CORREO']) ?></p>
                <p class="mb-1"><strong>Edad:</strong> <?= htmlspecialchars($usuario['EDAD']) ?> años</p>
                <p class="mb-1"><strong>Ciudad:</strong> <?= htmlspecialchars($usuario['CIUDAD']) ?></p>
                <p class="mb-2"><strong>Identificación:</strong> <?= htmlspecialchars($usuario['IDENTIFICACION']) ?></p>

                <!-- Formulario actualizar foto -->
                <form id="formFoto" enctype="multipart/form-data" class="d-md-flex align-items-center gap-2">
                    <input type="file" name="nueva_foto" class="form-control form-control-sm" accept="image/*" required>
                    <button type="submit" class="btn btn-sm btn-outline-primary mt-2 mt-md-0">Actualizar foto</button>
                </form>

                <div id="mensajeFoto" class="mt-2 text-muted small"></div>
            </div>
        </div>
    </div>
</div>

<!-- Biografía -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">📝 Biografía</h5>

        <form id="formBiografia">
            <textarea name="biografia" class="form-control mb-3" rows="4"
                placeholder="Escribe algo sobre ti..."><?= htmlspecialchars($usuario['biografia'] ?? '') ?></textarea>

            <button type="submit" class="btn btn-success">
                💾 Guardar biografía
            </button>
        </form>

        <div id="mensajeBiografia" class="mt-2 text-muted small"></div>
    </div>
</div>

<!-- Publicaciones -->
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">📢 Tus Publicaciones Activas</h5>

        <?php if (!empty($publicaciones)): ?>
            <div class="row">
                <?php foreach ($publicaciones as $post): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <?php if ($post['imagen']): ?>
                                <img src="/petfriend/public/uploads/posts/<?= htmlspecialchars($post['imagen']) ?>"
                                    class="card-img-top" alt="Imagen publicación"
                                    style="height: 180px; object-fit: cover;">
                            <?php endif; ?>

                            <div class="card-body">
                                <h6 class="card-title"><?= htmlspecialchars($post['titulo']) ?></h6>
                                <p class="card-text text-truncate"><?= htmlspecialchars($post['contenido']) ?></p>
                                <p class="text-muted small mb-0">
                                    Estado: <strong><?= htmlspecialchars($post['estado']) ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No tienes publicaciones activas.</div>
        <?php endif; ?>
    </div>
</div>


<script src="/petfriend/public/js/profile.js"></script>