<?php
class AdminController extends Controller
{
    public function dashboard()
    {
        $this->validateSession('admin');

        $userModel = $this->model('User');
        $postModel = $this->model('Post');
        $adopModel = $this->model('Adopcion');

        $usuarios = $userModel->getAll();
        $publicaciones = $postModel->getAllWithUser();
        $adopciones = $adopModel->getAllWithDetails();
        $estados = ['PENDIENTE', 'EN CURSO', 'APROBADA', 'RECHAZADA'];

        $this->view('admin/dashboard', [
            'title' => 'Panel de Administración',
            'NOMBRE' => $_SESSION['NOMBRE'] ?? 'Administrador',
            'usuarios' => $usuarios,
            'publicaciones' => $publicaciones,
            'adopciones' => $adopciones,
            'estados' => $estados
        ]);
    }

    public function index()
    {
        $this->dashboard();
    }

    public function actualizarRol()
    {
        $this->validateSession('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['new_role'])) {
            $id = $_POST['user_id'];
            $rol = $_POST['new_role'];
            $this->model('User')->actualizarRol($id, $rol);
        }

        header('Location: /petfriend/public/admin?rol_actualizado=1');
        exit;
    }

    public function actualizarEstadoPublicacion()
    {
        $this->validateSession('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['publicacion_id'] ?? null;
            $estadoTexto = $_POST['estado'] ?? null;

            $estadoMap = [
                'EN CURSO' => 1,
                'APROBADA' => 2,
                'RECHAZADA' => 3
            ];

            $estadoId = $estadoMap[$estadoTexto] ?? null;

            if ($id && $estadoId !== null) {
                $postModel = $this->model('Post');
                $postModel->updateEstado($id, $estadoTexto);

                $publicacion = $postModel->getById($id);
                $mascotaId = $publicacion['mascota_id'] ?? null;

                if ($mascotaId) {
                    $this->model('Adopcion')->actualizarEstadoPorMascota($mascotaId, $estadoId);
                }

                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                return;
            }
        }

        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Solicitud inválida']);
    }

    public function actualizarEstadoAdopcion()
    {
        $this->validateSession('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitud_id'], $_POST['estado'])) {
            $solicitudId = $_POST['solicitud_id'];
            $estadoTexto = $_POST['estado'];

            $estadoMap = [
                'EN CURSO' => 1,
                'APROBADA' => 2,
                'RECHAZADA' => 3
            ];
            $estadoId = $estadoMap[$estadoTexto] ?? null;

            if ($estadoId !== null) {
                $adopcionModel = $this->model('Adopcion');
                $postModel = $this->model('Post');

                // ✅ 1. Actualizar estado de la solicitud
                $adopcionModel->actualizarEstado($solicitudId, $estadoId);

                // ✅ 2. Obtener mascota_id asociada a esa solicitud (desde el modelo)
                $mascotaId = $adopcionModel->getMascotaIdBySolicitud($solicitudId);

                // ✅ 3. Si existe, actualizar publicaciones relacionadas
                if ($mascotaId) {
                    $postModel->updateEstadoByMascota($mascotaId, $estadoTexto);
                }
            }
        }

        header('Location: /petfriend/public/admin?estado_adopcion_actualizado=1');
        exit;
    }

    public function cambiarContrasena()
    {
        $this->validateSession('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_usuario'] ?? null;
            $nueva = trim($_POST['nueva_password'] ?? '');
            $confirmar = trim($_POST['confirmar_password'] ?? '');

            if (!$id || !$nueva || !$confirmar) {
                echo "Faltan datos.";
                return;
            }

            if ($nueva !== $confirmar) {
                echo "Las contraseñas no coinciden.";
                return;
            }

            if (strlen($nueva) < 6) {
                echo "La contraseña debe tener al menos 6 caracteres.";
                return;
            }

            $hash = password_hash($nueva, PASSWORD_DEFAULT);
            $this->model('User')->actualizarPassword($id, $hash);

            header("Location: /petfriend/public/admin?password_cambiada=1");
            exit;
        }

        echo "Método inválido.";
    }
}
