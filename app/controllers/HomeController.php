<?php
class HomeController extends Controller
{
  public function index()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['ID_USUARIO'])) {
        // Redirigir según el rol
        if ($_SESSION['ROL'] === 'admin') {
            header('Location: /petfriend/public/admin');
        } else {
            header('Location: /petfriend/public/user');
        }
    } else {
        // Si no hay sesión activa, redirigir al login
        header('Location: /petfriend/public/auth/login');
    }

    exit;
}

}

