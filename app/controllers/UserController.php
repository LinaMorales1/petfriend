<?php
class UserController extends Controller
{
    public function index()
    {
        $this->validateSession('usuario');
        $postModel = $this->model('Post');
        $posts = $postModel->getAll();

        $this->view('user/dashboard', [
            'title' => 'Inicio - Pet Friend',
            'publicaciones' => $posts,
        ], 'layouts/user');
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
                $ciudad = $_SESSION['CIUDAD'] ?? 'Desconocida';
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

    public function actualizarPerfil()
    {
        $this->validateSession('usuario');

        $userModel = $this->model('User');
        $user = $userModel->getById($_SESSION['ID_USUARIO']);
        $mensaje = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_perfil'])) {
            $id = $_SESSION['ID_USUARIO'];
            $nombre = trim($_POST['nombre']);
            $apellidos = trim($_POST['apellidos']);
            $ciudad = trim($_POST['ciudad']);
            $edad = intval($_POST['edad']);
            $correo = trim($_POST['correo']);

            if ($nombre && $apellidos && $correo) {
                $userModel->actualizarPerfil($id, $nombre, $apellidos, $ciudad, $edad, $correo);
                $mensaje = "✅ Perfil actualizado correctamente.";
                $user = $userModel->getById($_SESSION['ID_USUARIO']);
            } else {
                $mensaje = "❌ Por favor completa todos los campos obligatorios.";
            }
        }

        $this->view('user/configuracion', [
            'usuario' => $user,
            'mensaje' => $mensaje,
            'title' => 'Configuración - Pet Friend'
        ], 'layouts/user');
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
}
