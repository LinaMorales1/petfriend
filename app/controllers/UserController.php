<?php

class UserController extends Controller
{
    public function index()
    {
        $this->validateSession('usuario');
        $postModel = $this->model('Post');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
            $comentario = trim($_POST['comentario']);
            $publicacionId = intval($_POST['publicacion_id']);
            $usuarioId = $_SESSION['ID_USUARIO'];

            if (!empty($comentario)) {
                $postModel->guardarComentario($usuarioId, $publicacionId, $comentario);
            }

            header("Location: /petfriend/public/user");
            exit;
        }

        $posts = $postModel->getAll();

        $comentariosPorPub = [];
        $likesPorPub = [];

        foreach ($posts as $post) {
            $comentariosPorPub[$post['id']] = $postModel->obtenerComentarios($post['id']);
            $likesPorPub[$post['id']] = $postModel->contarLikes($post['id']);
        }

        $this->view('user/dashboard', [
            'title' => 'Inicio - Pet Friend',
            'publicaciones' => $posts,
            'comentariosPorPub' => $comentariosPorPub,
            'likesPorPub' => $likesPorPub,
        ], 'layouts/user');
    }

    public function likeAjax()
    {
        $this->validateSession('usuario');
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idUsuario = $_SESSION['ID_USUARIO'];
            $idPublicacion = intval($_POST['publicacion_id'] ?? 0);

            if (!$idPublicacion) {
                echo json_encode(['success' => false, 'message' => 'ID inválido']);
                return;
            }

            $postModel = $this->model('Post');

            if ($postModel->usuarioDioLike($idUsuario, $idPublicacion)) {
                $postModel->quitarLike($idUsuario, $idPublicacion);
                $action = 'dislike';
            } else {
                $postModel->darLike($idUsuario, $idPublicacion);
                $action = 'like';
            }

            $totalLikes = $postModel->contarLikes($idPublicacion);

            echo json_encode([
                'success' => true,
                'action' => $action,
                'likes' => $totalLikes
            ]);
        }
    }

    public function comentarAjax()
    {
        $this->validateSession('usuario');
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idUsuario = $_SESSION['ID_USUARIO'];
            $idPublicacion = intval($_POST['publicacion_id'] ?? 0);
            $comentario = trim($_POST['comentario'] ?? '');

            if ($idPublicacion && $comentario) {
                $postModel = $this->model('Post');
                $postModel->guardarComentario($idUsuario, $idPublicacion, $comentario);
                $comentarios = $postModel->obtenerComentarios($idPublicacion);

                echo json_encode([
                    'success' => true,
                    'comentarios' => $comentarios
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Comentario inválido']);
            }
        }
    }
    public function profile()
    {
        $this->validateSession('usuario');

        $userModel = $this->model('User');
        $postModel = $this->model('Post');
        $user = $userModel->getById($_SESSION['ID_USUARIO']);
        $posts = $postModel->getByUser($_SESSION['ID_USUARIO'], null, "COMPLETADA");

        $this->view('user/profile', [
            'usuario' => $user,
            'publicaciones' => $posts,
            'title' => 'Mi perfil - Pet Friend'
        ], 'layouts/user');
    }

    public function publicaciones()
    {
        $this->validateSession('usuario');

        $mascotas = $this->model('Mascota')->getAll(); // 🔁 cambia aquí

        $this->view('user/publicaciones', [
            'title' => 'Publicaciones',
            'mascotas' => $mascotas
        ], 'layouts/user');
    }

    public function create()
    {
        $this->validateSession('usuario');
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $titulo = trim($_POST['titulo'] ?? '');
                $contenido = trim($_POST['contenido'] ?? '');
                $mascotaId = intval($_POST['mascota_id'] ?? 0);
                $usuarioId = $_SESSION['ID_USUARIO'];
                $ciudad = $_SESSION['CIUDAD'] ?? '';
                $imagenNombre = null;

                // 🔍 Validaciones
                if (!$titulo) {
                    echo json_encode(['success' => false, 'message' => '❌ Debes escribir un título.']);
                    return;
                }

                if (!$contenido) {
                    echo json_encode(['success' => false, 'message' => '❌ Debes escribir una descripción.']);
                    return;
                }

                if (!$mascotaId) {
                    echo json_encode(['success' => false, 'message' => '❌ Debes seleccionar una mascota.']);
                    return;
                }

                // ✅ Subida de imagen (opcional)
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $imagenNombre = uniqid('post_') . '.' . $ext;
                    move_uploaded_file($_FILES['imagen']['tmp_name'], "../public/uploads/posts/" . $imagenNombre);
                }

                // ✅ Crear publicación
                $data = [
                    'usuario_id' => $usuarioId,
                    'titulo' => $titulo,
                    'contenido' => $contenido,
                    'imagen' => $imagenNombre,
                    'ciudad' => $ciudad,
                    'mascota_id' => $mascotaId
                ];

                $postModel = $this->model('Post');
                $post = $postModel->create($data);

                // ✅ Crear solicitud de adopción con estado = 1 (EN CURSO)
                $estadoId = 1; // EN CURSO

                $adopcionModel = $this->model('Adopcion');
                $adopcionModel->crearSolicitud([
                    'id_usuario' => $usuarioId,
                    'id_mascota' => $mascotaId,
                    'ciudad' => $ciudad,
                    'fecha' => date('Y-m-d'),
                    'estado' => $estadoId // 👈 ahora es un número
                ]);

                echo json_encode([
                    'success' => true,
                    'post' => $post,
                    'message' => '✅ Publicación creada correctamente.'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => '❌ Error: ' . $e->getMessage()
            ]);
        }
    }

    public function estado()
    {
        $this->validateSession('usuario');

        $id_usuario = $_SESSION['ID_USUARIO'];
        $estado = $_GET['estado'] ?? 'EN CURSO';

        $postModel = $this->model('Post');
        $postModel->eliminarCompletadas(); // limpia publicaciones con estado COMPLETADA
        $publicaciones = $postModel->getByUserAndEstado($id_usuario, $estado);

        $this->view('user/estado_publicaciones', [
            'publicaciones' => $publicaciones,
            'estado' => $estado,
            'title' => 'Mis Publicaciones'
        ], 'layouts/user');
    }

    public function actualizarEstado()
    {
        $this->validateSession('usuario');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $nuevoEstado = $_POST['estado'] ?? null;

            if ($id && $nuevoEstado) {
                $this->model('Post')->updateEstado($id, $nuevoEstado);
            }
        }

        $estado = $_GET['estado'] ?? 'EN CURSO';
        header("Location: /petfriend/public/user/estado?estado=" . urlencode($estado));
        exit;
    }

    public function configuracion()
    {
        $this->validateSession('usuario');
        $userModel = $this->model('User');
        $user = $userModel->getById($_SESSION['ID_USUARIO']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombres = trim($data['nombres'] ?? '');
            $apellidos = trim($data['apellidos'] ?? '');
            $email = trim($data['email'] ?? '');

            if ($nombres && $apellidos && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $userModel->configracion($_SESSION['ID_USUARIO'], $nombres, $apellidos, $email);
                header("Location: /petfriend/public/user/configuracion");
                exit;
            } else {
                echo "Datos inválidos.";
            }
        }

        $this->view('user/configuracion', [
            'usuario' => $user,
            'title' => 'Configuración - Pet Friend'
        ], 'layouts/user');
    }

    public function updatePhoto()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['ID_USUARIO'])) {
            echo "Error: Usuario no autenticado.";
            return;
        }

        $usuarioId = $_SESSION['ID_USUARIO'];
        $foto = $_FILES['nueva_foto'] ?? null;

        if ($foto && $foto['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
            $nombreArchivo = 'perfil_' . uniqid() . '.' . $extension;

            $carpeta = __DIR__ . '/../../public/uploads/perfiles/';
            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $rutaDestino = $carpeta . $nombreArchivo;

            if (move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
                $userModel = $this->model('User');
                $userModel->updateFoto($usuarioId, $nombreArchivo);

                echo 'ok:' . $nombreArchivo;
            } else {
                echo "Error al mover la imagen.";
            }
        } else {
            echo "No se recibió la imagen correctamente.";
        }
    }

    public function cambiarContrasena()
    {
        $this->validateSession('usuario');

        $mensajePassword = null;
        $userModel = $this->model('User');
        $user = $userModel->getById($_SESSION['ID_USUARIO']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_password'])) {
            $id = $_SESSION['ID_USUARIO'];
            $nuevaContrasena = trim($_POST['nueva_password']);
            $confirmarContrasena = trim($_POST['confirmar_password']);

            if ($nuevaContrasena && $nuevaContrasena === $confirmarContrasena && strlen($nuevaContrasena) >= 8) {
                $nuevaContrasenaHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
                $userModel->cambiarContrasena($id, $nuevaContrasenaHash);
                $mensajePassword = "✅ Contraseña actualizada correctamente.";
            } else {
                $mensajePassword = "❌ La contraseña es inválida o no coincide.";
            }
        }

        $this->view('user/configuracion', [
            'usuario' => $user,
            'mensajePassword' => $mensajePassword,
            'title' => 'Configuración - Pet Friend'
        ], 'layouts/user');
    }

    public function bandeja_mensajes()
    {
        $this->validateSession('usuario');

        $mensajeModel = $this->model('Mensaje');
        $usuarios = $this->model('User')->getAll();
        $mensajes = $mensajeModel->obtenerMensajes($_SESSION['ID_USUARIO']);

        $this->view('user/bandeja_mensajes', [
            'mensajes' => $mensajes,
            'usuarios' => $usuarios,
            'title' => 'Mis Mensajes'
        ], 'layouts/user');
    }

    public function enviar_mensaje()
    {
        $this->validateSession('usuario');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $emisorId = $_SESSION['ID_USUARIO'];
            $receptorId = intval($_POST['receptor_id']);
            $mensaje = trim($_POST['mensaje']);

            $this->model('Mensaje')->enviar($emisorId, $receptorId, $mensaje);
        }

        header('Location: /petfriend/public/user/bandeja_mensajes');
        exit;
    }

    public function acerca_terminos()
    {
        $this->validateSession('usuario');

        $this->view('user/terminos_condiciones', [
            'title' => 'Términos y Condiciones - Pet Friend'
        ], 'layouts/user');
    }

    public function like()
    {
        $this->validateSession('usuario');

        if (!isset($_GET['like'])) {
            echo "ID de publicación no proporcionado";
            return;
        }

        $idPublicacion = intval($_GET['like']);
        $idUsuario = $_SESSION['ID_USUARIO'];

        $postModel = $this->model('Post');

        // Verifica si ya dio like
        if ($postModel->usuarioDioLike($idUsuario, $idPublicacion)) {
            $postModel->quitarLike($idUsuario, $idPublicacion);
        } else {
            $postModel->darLike($idUsuario, $idPublicacion);
        }

        header('Location: /petfriend/public/user');
        exit;
    }
    public function updateBiography()
    {
        $this->validateSession('usuario');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['ID_USUARIO'])) {
            $biografia = trim($_POST['biografia'] ?? '');
            $idUsuario = $_SESSION['ID_USUARIO'];

            $userModel = $this->model('User');
            $userModel->updateBiografia($idUsuario, $biografia);

            echo "ok";
        } else {
            echo "error";
        }
    }
    public function editar_publicacion()
    {
        $this->validateSession('usuario');
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo "ID inválido.";
            return;
        }

        $postModel = $this->model('Post');
        $publicacion = $postModel->getById($id);

        if (!$publicacion) {
            echo "Publicación no encontrada.";
            return;
        }

        $this->view('user/editar_publicacion', [
            'publicacion' => $publicacion,
            'title' => 'Editar publicación'
        ], 'layouts/user');
    }
    public function actualizar_publicacion()
    {
        $this->validateSession('usuario');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $titulo = trim($_POST['titulo']);
            $contenido = trim($_POST['contenido']);
            $imagenNombre = null;

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $imagenNombre = uniqid('post_') . '.' . $ext;
                move_uploaded_file($_FILES['imagen']['tmp_name'], "../public/uploads/posts/" . $imagenNombre);
            }

            $postModel = $this->model('Post');

            $postModel->actualizar([
                'id' => $id,
                'titulo' => $titulo,
                'contenido' => $contenido,
                'imagen' => $imagenNombre // puede ser null
            ]);

            header("Location: /petfriend/public/user/estado");
            exit;
        }
    }
}
