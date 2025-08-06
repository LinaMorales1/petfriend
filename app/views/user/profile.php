<?php
$foto = !empty($usuario['FOTO'])
    ? "/petfriend/public/uploads/perfiles/" . $usuario['FOTO']
    : "/petfriend/public/img/default.jpg";
?>

<div class="card mb-4 shadow-sm">
    <div class="card-body d-flex align-items-center">
        <img id="fotoPerfil" src="<?= $foto ?>" alt="Foto de perfil" class="rounded-circle me-4" style="width: 100px; height: 100px; object-fit: cover;">
        <div>
            <h4 class="mb-1"><?= htmlspecialchars($usuario['NOMBRES'] . ' ' . $usuario['APELLIDOS']) ?></h4>
            <p class="mb-1 text-muted">Correo: <?= htmlspecialchars($usuario['CORREO']) ?></p>
            <p class="mb-1 text-muted">Edad: <?= htmlspecialchars($usuario['EDAD']) ?> años</p>
            <p class="mb-1 text-muted">Ciudad: <?= htmlspecialchars($usuario['CIUDAD']) ?></p>
            <p class="mb-1 text-muted">Identificación: <?= htmlspecialchars($usuario['IDENTIFICACION']) ?></p>
            <form id="formFoto" enctype="multipart/form-data" class="mt-2">
                <input type="file" name="nueva_foto" class="form-control form-control-sm mb-2" accept="image/*" required>
                <button type="submit" class="btn btn-sm btn-outline-primary">Actualizar foto</button>
            </form>
            <div id="mensajeFoto" class="mt-2 text-muted"></div>
        </div>
    </div>

    <!-- Biografía -->
    <div class="bio-box my-4">
        <h5>Biografía</h5>
        <form method="POST">
            <textarea name="biografia" rows="3" class="form-control mb-2"><?= htmlspecialchars($usuario['biografia'] ?? '') ?></textarea>
            <button type="submit" class="btn btn-sm btn-success">Guardar biografía</button>
        </form>
    </div>

    <!-- Publicaciones -->
    <?php if (empty($publicaciones)): ?>
        <div class="alert alert-info">No tienes publicaciones activas.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($publicaciones as $post): ?>
                <div class="list-group-item mb-2 shadow-sm">
                    <h5 class="mb-1"><?= htmlspecialchars($post['titulo']) ?></h5>
                    <p class="mb-1"><?= nl2br(htmlspecialchars($post['contenido'])) ?></p>
                    <small class="text-muted">Fecha: <?= htmlspecialchars($post['fecha']) ?> | Estado: <?= htmlspecialchars($post['estado']) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="/petfriend/public/js/profile.js"></script>