<?php
//print_r($publicaciones)

?>

<h3 class="mb-4">Publicaciones recientes</h3>

<div class="row">
    <?php foreach ($publicaciones as $pub): ?>
        <div class="col-12 gy-3">
            <div class="card">
                <div class="card-header">
                    <h3><?= htmlspecialchars($pub['titulo']) ?></h3>
                </div>
                <div class="card-body">
                    <div class="vstack gap-3">
                        <div>
                            Creado por: <small class="text-muted">- <?= htmlspecialchars($pub['NOMBRES']) . ' ' . htmlspecialchars($pub['APELLIDOS']) ?></small>
                        </div>
                        <div>
                            Contenido: <?= nl2br(htmlspecialchars($pub['contenido'])) ?>
                        </div>
                        <div style="height: 300px;">
                            <img src="/petfriend/public/uploads/posts/<?= htmlspecialchars($pub['imagen']) ?>" class="object-fit-cover border rounded w-100 h-100" alt="Imagen publicación">
                        </div>
                    </div>
                    <div>

                    </div>
                </div>
            </div>

            <!-- <div class="reactions mt-2">
                <a href="?like=<?= $pub['id'] ?>" class="btn btn-outline-primary btn-sm">👍 Me gusta (<?= $likesPorPub[$pub['id']] ?? 0 ?>)</a>
            </div>

            <div class="mt-3">
                <strong>Comentarios:</strong>
                <div class="mt-2">
                    <?php if (!empty($comentariosPorPub[$pub['id']])): ?>
                        <?php foreach ($comentariosPorPub[$pub['id']] as $comentario): ?>
                            <p><strong><?= htmlspecialchars($comentario['NOMBRES']) ?>:</strong> <?= htmlspecialchars($comentario['contenido']) ?></p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p><em>No hay comentarios aún.</em></p>
                    <?php endif; ?>
                    <form method="POST" class="mt-2">
                        <div class="input-group">
                            <input type="hidden" name="publicacion_id" value="<?= $pub['id'] ?>">
                            <input type="text" name="comentario" class="form-control" placeholder="Escribe un comentario..." required>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                </div>
            </div> -->
        </div>
    <?php endforeach; ?>
</div>