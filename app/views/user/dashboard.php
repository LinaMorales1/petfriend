<?php
$comentariosPorPub = $comentariosPorPub ?? [];
$likesPorPub = $likesPorPub ?? [];
?>
<link rel="stylesheet" href="/petfriend/public/css/inicio.css?v=5">

<h3 class="mb-4">Publicaciones recientes</h3>

<div class="row">
    <?php foreach ($publicaciones as $pub): ?>
        <div class="col-12 gy-3">
            <div class="card">
                <div class="card-header">
                    <h3><?= htmlspecialchars($pub['titulo']) ?></h3>
                </div>
                <div class="card-body">
                    <div class="vstack gap-4">
                        <!-- Info del creador -->
                        <div>
                            Creado por:
                            <small class="text-muted">
                                - <?= htmlspecialchars($pub['NOMBRES']) . ' ' . htmlspecialchars($pub['APELLIDOS']) ?>
                            </small>
                        </div>

                        <!-- Contenido -->
                        <div>
                            Contenido: <?= nl2br(htmlspecialchars($pub['contenido'])) ?>
                        </div>
                        <div>
                            Ciudad: <?= nl2br(htmlspecialchars($pub['ciudad'])) ?>
                        </div>
                        <!-- Imagen -->
                        <?php if (!empty($pub['imagen'])): ?>
                            <div class="post-image-container">
                                <img src="/petfriend/public/uploads/posts/<?= htmlspecialchars($pub['imagen']) ?>"
                                    class="post-image adaptive-image"
                                    alt="Imagen publicación">

                            </div>
                        <?php endif; ?>

                        <!-- Botón mensaje -->
                        <div>
                            <a href="/petfriend/public/user/bandeja_mensajes" class="btn btn-primary">MENSAJE</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reacciones (Me gusta) -->
            <div class="reactions mt-2">
                <button class="btn btn-outline-primary btn-like btn-sm" data-id="<?= $pub['id'] ?>">
                    👍 Me gusta (<span class="like-count"><?= $likesPorPub[$pub['id']] ?? 0 ?></span>)
                </button>
            </div>


            <!-- Comentarios -->
            <div class="mt-3">
                <strong>Comentarios:</strong>
                <div class="comentarios mt-2">
                    <?php if (!empty($comentariosPorPub[$pub['id']])): ?>
                        <?php foreach ($comentariosPorPub[$pub['id']] as $comentario): ?>
                            <p>
                                <strong><?= htmlspecialchars($comentario['NOMBRES']) ?>:</strong>
                                <?= htmlspecialchars($comentario['contenido']) ?>
                            </p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p><em>No hay comentarios aún.</em></p>
                    <?php endif; ?>
                </div>

                <!-- Formulario para nuevo comentario -->
                <form class="form-comentario mt-2" data-id="<?= $pub['id'] ?>">
                    <input type="hidden" name="publicacion_id" value="<?= $pub['id'] ?>">
                    <div class="input-group">
                        <input type="text" name="comentario" class="form-control" placeholder="Escribe un comentario..." required>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>

            </div>
        </div>
    <?php endforeach; ?>
</div>