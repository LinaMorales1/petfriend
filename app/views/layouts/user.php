<?php
function isActive($route, $currentUrl)
{
    return $currentUrl === $route ? 'active' : '';
}
?>

<link rel="stylesheet" href="/petfriend/public/css/inicio.css">

<div class="d-flex">
    <!-- Sidebar -->
    <div id="sidebar" class="text-white p-3">
        <h4 id="titulo">Pet Friend</h4>
        <ul id="barra" class="nav flex-column mb-4">
            <li class="nav-item"><a class="nav-link text-white <?= isActive('user', $currentUrl) ?>" href="/petfriend/public/user">Inicio</a></li>
            <li class="nav-item">
                <a class="nav-link text-white <?= isActive('user/profile', $currentUrl) ?>" href="/petfriend/public/user/profile">Perfil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#submenuAdopciones" role="button" aria-expanded="false" aria-controls="submenuAdopciones">Adopciones</a>
                <div class="collapse ps-3" id="submenuAdopciones">
                    <a class="nav-link text-white" href="/petfriend/public/user/publicaciones">Publicar</a>
                    <a class="nav-link text-white" href="/petfriend/public/user/estado">Estado</a>

                </div>
            </li>
            <li class="nav-item"><a class="nav-link text-white" href="/petfriend/public/user/bandeja_mensajes">Mensajes</a></li>

            <li class="nav-item"><a class="nav-link text-white" href="/petfriend/public/user/configuracion">Configuración</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/petfriend/public/user/acerca_terminos">Términos</a></li>
            <a class="nav-link text-white" href="/petfriend/public/auth/logout">Cerrar sesión</a>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div id="main-content" class="flex-grow-1">
        <button class="btn btn-sm btn-secondary m-3" onclick="toggleSidebar()">☰ Menú</button>
        <div id="main-content" class="flex-grow-1">
            <div class="container py-4">
                <?= $viewContent ?>
            </div>
        </div>
    </div>
</div>

<script>
    const mostrarSeccion = (id, event) => {
        if (event) event.preventDefault();

        // Oculta todas las secciones
        document.querySelectorAll('.seccion').forEach(sec => {
            sec.style.display = 'none';
            sec.classList.remove('activa');
        });

        // Muestra la sección seleccionada
        const seccionActiva = document.getElementById(id);
        if (seccionActiva) {
            seccionActiva.style.display = 'block';
            seccionActiva.classList.add('activa');
        }

        // Actualiza las pestañas activas
        document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
        if (event?.target) {
            event.target.classList.add('active');
        }
    }
</script>

<script src="/petfriend/public/js/dashboard.js"></script>