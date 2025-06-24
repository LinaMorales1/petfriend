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

        $this->view('user/publicaciones', [
            'title' => 'Publicaciones'
        ], 'layouts/user');
    }

    public function updatePhoto()
    {
        $this->validateSession('usuario');

        $id = $_SESSION['ID_USUARIO'];

        if (!isset($_FILES['nueva_foto']) || $_FILES['nueva_foto']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo "Archivo inválido";
            return;
        }

        $archivo = $_FILES['nueva_foto'];
        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $nombreArchivo = $id . '.' . $ext;
        $rutaDestino = "../public/uploads/perfiles/" . $nombreArchivo;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            http_response_code(500);
            echo "Error al mover el archivo.";
            return;
        }

        $this->model('User')->updateFoto($id, $nombreArchivo);
        echo "ok:" . $nombreArchivo;
    }

    public function create()
    {
        $this->validateSession('usuario');
        header('Content-Type: application/json');
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $titulo = trim($_POST['titulo'] ?? '');
                $contenido = trim($_POST['contenido'] ?? '');
                $usuarioId = $_SESSION['ID_USUARIO'];
                $imagenNombre = null;

                if (!$titulo || !$contenido) {
                    echo json_encode(['success' => false, 'message' => 'Título y contenido son obligatorios.']);
                    return;
                }


                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $imagenNombre = uniqid('post_') . '.' . $ext;
                    move_uploaded_file($_FILES['imagen']['tmp_name'], "../public/uploads/posts/". $imagenNombre);
                }
                
                $data = [
                    'usuario_id' => $usuarioId,
                    'titulo' => $titulo,
                    'contenido' => $contenido,
                    'imagen' => $imagenNombre,
                ];

                $postModel = $this->model('Post');
                $post = $postModel->create($data);

                echo json_encode(['success' => true, 'post' => $post]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
