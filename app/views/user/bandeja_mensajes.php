
<div class="container py-4">
    <h2 class="mb-4 text-center">📨 Mensajes</h2>

    <form method="POST" action="/petfriend/public/user/enviar_mensaje" class="mb-4">
        <div class="row g-2">
            <div class="col-md-4">
                <select name="receptor_id" class="form-select" required>
                    <option value="">Selecciona receptor</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <?php if ($usuario['ID_USUARIO'] != $_SESSION['ID_USUARIO']): ?>
                            <option value="<?= $usuario['ID_USUARIO'] ?>">
                                <?= $usuario['NOMBRES'] . ' ' . $usuario['APELLIDOS'] ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="mensaje" class="form-control" placeholder="Escribe tu mensaje..." required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Enviar</button>
            </div>
        </div>
    </form>

    <div class="list-group">
        <?php foreach ($mensajes as $msg): ?>
            <div class="list-group-item">
                <strong><?= $msg['nombre_emisor'] ?> → <?= $msg['nombre_receptor'] ?>:</strong>
                <?= htmlspecialchars($msg['mensaje']) ?>
                <div class="text-muted small"><?= $msg['fecha_envio'] ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

