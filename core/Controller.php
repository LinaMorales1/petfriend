<?php
class Controller
{
    public function model($model)
    {
        require_once "../app/models/$model.php";
        return new $model;
    }

    public function view($view, $data = [], $layout = null)
    {
        // Extraer variables para uso dentro de las vistas
        extract($data);

        // Obtener la URL actual (para activar menús, etc.)
        $currentUrl = $_GET['url'] ?? 'home';

        // Capturar el contenido de la vista
        ob_start();
        require_once "../app/views/$view.php";
        $viewContent = ob_get_clean();

        if ($layout) {
            // Si se especificó un layout (ej: layouts/user o layouts/admin)
            ob_start();
            require_once "../app/views/{$layout}.php";
            $content = ob_get_clean();
        } else {
            // Si no hay layout, usar el contenido de la vista directamente
            $content = $viewContent;
        }

        // Renderizar la vista final
        require_once "../app/views/layouts/main.php";
    }

    /**
     * Validar sesión iniciada y opcionalmente el rol del usuario
     * @param string|null $requiredRole  Ej: 'admin' o 'usuario'
     */
    protected function validateSession($requiredRole = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['ID_USUARIO'])) {
            header('Location: /petfriend/public/auth/login');
            exit;
        }

        if ($requiredRole && (!isset($_SESSION['ROL']) || $_SESSION['ROL'] !== $requiredRole)) {
            header('Location: /petfriend/public/auth/login');
            exit;
        }
    }

    /**
     * Redirigir si el usuario ya inició sesión según su rol
     */
    protected function redirectIfLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['ROL'])) {
            if ($_SESSION['ROL'] === 'usuario') {
                header('Location: /petfriend/public/user');
                exit;
            } elseif ($_SESSION['ROL'] === 'admin') {
                header('Location: /petfriend/public/admin');
                exit;
            }
        }
    }
}

