<div class="d-flex">
    <nav class="p-3 bg-dark text-white" style="min-width: 220px; height: 100vh;">
        <h4>Administrador</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="#usuarios" class="nav-link text-white">Usuarios</a></li>
            <li class="nav-item"><a href="#publicaciones" class="nav-link text-white">Publicaciones</a></li>
            <li class="nav-item"><a href="#adopciones" class="nav-link text-white">Adopciones</a></li>
            <a class="nav-link text-white" href="/petfriend/public/auth/logout">Cerrar sesión</a>
        </ul>
    </nav>

    <div class="flex-grow-1 p-4">
        <h2 class="mb-4">Panel de Administración</h2>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-bg-primary shadow">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios registrados</h5>
                        <p class="card-text fs-4"><?= count($usuarios) ?> usuarios</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-success shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Publicaciones</h5>
                        <p class="card-text fs-4"><?= count($publicaciones) ?> publicaciones</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-warning shadow">
                    <div class="card-body">
                        <h5 class="card-title">Solicitudes de Adopción</h5>
                        <p class="card-text fs-4"><?= count($adopciones) ?> solicitudes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes de éxito -->
        <?php if (isset($_GET['password_cambiada'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ Contraseña actualizada correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['rol_actualizado'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ Rol actualizado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['estado_publicacion_actualizado'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ Estado de publicación actualizado.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['estado_adopcion_actualizado'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ Estado de adopción actualizado.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <!-- USUARIOS -->
        <section id="usuarios">
            <h4 class="mb-3">Usuarios Registrados</h4>
            <table class="table table-bordered table-hover table-sm">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Edad</th>
                        <th>Celular</th>
                        <th>Ciudad</th>
                        <th>Documento</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $u['ID_USUARIO'] ?></td>
                            <td><?= htmlspecialchars($u['NOMBRES']) ?></td>
                            <td><?= htmlspecialchars($u['APELLIDOS']) ?></td>
                            <td><?= htmlspecialchars($u['CORREO']) ?></td>
                            <td><?= htmlspecialchars($u['EDAD']) ?></td>
                            <td><?= htmlspecialchars($u['CELULAR']) ?></td>
                            <td><?= htmlspecialchars($u['CIUDAD'] ?? 'Ciudad desconocida') ?></td>
                            <td><?= htmlspecialchars($u['IDENTIFICACION']) ?></td>
                            <td>
                                <form method="POST" action="/petfriend/public/admin/actualizarRol" class="d-flex">
                                    <input type="hidden" name="user_id" value="<?= $u['ID_USUARIO'] ?>">
                                    <select name="new_role" class="form-select form-select-sm me-2">
                                        <option value="usuario" <?= $u['ROL'] === 'usuario' ? 'selected' : '' ?>>usuario</option>
                                        <option value="admin" <?= $u['ROL'] === 'admin' ? 'selected' : '' ?>>admin</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Cambiar</button>
                                </form>
                            </td>
                            <td class="d-flex flex-column gap-1">
                                <form method="POST" action="/petfriend/public/admin/eliminarUsuario" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                    <input type="hidden" name="id" value="<?= $u['ID_USUARIO'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm w-100">Eliminar</button>
                                </form>
                                <button class="btn btn-sm btn-warning w-100" data-bs-toggle="modal" data-bs-target="#modalPass<?= $u['ID_USUARIO'] ?>">Contraseña</button>
                            </td>
                        </tr>

                        <!-- MODAL CONTRASEÑA -->
                        <div class="modal fade" id="modalPass<?= $u['ID_USUARIO'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="/petfriend/public/admin/cambiarContrasena">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Cambiar contraseña</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_usuario" value="<?= $u['ID_USUARIO'] ?>">
                                            <div class="mb-3">
                                                <label>Nueva contraseña</label>
                                                <input type="password" name="nueva_password" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Confirmar contraseña</label>
                                                <input type="password" name="confirmar_password" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- ... NAV y ESTADÍSTICAS igual que antes ... -->

        <!-- PUBLICACIONES -->
        <section id="publicaciones" class="mt-5">
            <h4>Publicaciones Recientes</h4>

            <!-- Alerta AJAX -->
            <div id="alerta-estado" class="alert alert-success alert-dismissible fade show d-none" role="alert">
                ✅ Estado de publicación actualizado correctamente.
                <button type="button" class="btn-close" onclick="document.getElementById('alerta-estado').classList.add('d-none');"></button>
            </div>

            <div class="row">
                <?php foreach ($publicaciones as $p): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($p['titulo']) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    Por <?= htmlspecialchars($p['NOMBRES'] . ' ' . $p['APELLIDOS']) ?> | Estado: <?= htmlspecialchars($p['estado']) ?>
                                </h6>
                                <p class="card-text"><?= nl2br(htmlspecialchars($p['contenido'])) ?></p>

                                <!-- AJAX - Cambiar estado -->
                                <form method="POST" class="form-estado-publicacion d-flex mt-2" data-id="<?= $p['id'] ?>">
                                    <select name="estado" class="form-select form-select-sm me-2">
                                        <?php foreach ($estados as $e): ?>
                                            <option value="<?= $e ?>" <?= $e === $p['estado'] ? 'selected' : '' ?>><?= $e ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Actualizar</button>
                                </form>

                                <form method="POST" action="/petfriend/public/admin/eliminarPublicacion" onsubmit="return confirm('¿Eliminar esta publicación?');" class="mt-2">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar publicación</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Script AJAX -->
        <script src="/petfriend/public/js/dashboard.js"></script>


        <!-- ADOPCIONES -->
        <section id="adopciones" class="mt-5">
            <h4>Solicitudes de Adopción</h4>
            <table class="table table-striped table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Mascota</th>
                        <th>Estado</th>
                        <th>Ciudad</th>
                        <th>Fecha</th>
                        <th>Cambiar Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach ($adopciones as $a): ?>
                        <?php
                        $badge = match ($a['ESTADO']) {
                            'APROBADA' => 'success',
                            'EN CURSO' => 'warning',
                            'RECHAZADA' => 'danger',
                            default => 'secondary',
                        };
                        ?>
                        <tr>
                            <td><?= $a['ID'] ?></td>
                            <td><?= htmlspecialchars($a['NOMBRES'] . ' ' . $a['APELLIDOS']) ?></td>
                            <td><?= htmlspecialchars($a['ESPECIE']) ?></td>
                            <td><span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($a['ESTADO']) ?></span></td>
                            <td><?= htmlspecialchars($a['CIUDAD']) ?></td>
                            <td><?= htmlspecialchars($a['FECHA']) ?></td>
                            <td>
                                <form method="POST" action="/petfriend/public/admin/actualizarEstadoAdopcion" class="d-flex">
                                    <input type="hidden" name="solicitud_id" value="<?= $a['ID'] ?>">
                                    <select name="estado" class="form-select form-select-sm me-2">
                                        <?php foreach ($estados as $e): ?>
                                            <option value="<?= $e ?>" <?= $e === $a['ESTADO'] ? 'selected' : '' ?>><?= $e ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="/petfriend/public/admin/eliminarAdopcion" onsubmit="return confirm('¿Eliminar esta solicitud?');">
                                    <input type="hidden" name="id" value="<?= $a['ID'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</div>