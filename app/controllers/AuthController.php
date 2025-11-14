<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\Usuario;

class AuthController extends Controller
{
    private $usuarioModel;

    public function __construct()
    {
        parent::__construct();
        $this->usuarioModel = new Usuario();
    }

    /**
     * Mostrar formulario de login
     */
    public function showLogin(Request $request)
    {
        // Si ya está autenticado, redirigir
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }

        $this->view('auth.login', [
            'noLayout' => true,
            'title' => 'Iniciar Sesión'
        ]);
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        if (!$request->isPost()) {
            $this->redirect('login');
        }

        $username = $request->post('username');
        $password = $request->post('password');

        // Validar datos
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Por favor complete todos los campos';
            $this->redirect('login');
        }

        // Buscar usuario
        $user = $this->usuarioModel->findByUsername($username);

        if (!$user) {
            $_SESSION['error'] = 'Usuario o contraseña incorrectos';
            $this->redirect('login');
        }

        // Verificar contraseña
        if (!$this->usuarioModel->verifyPassword($password, $user['password'])) {
            $_SESSION['error'] = 'Usuario o contraseña incorrectos';
            $this->redirect('login');
        }

        // Iniciar sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'nombre' => $user['nombre'],
            'apellido' => $user['apellido'],
            'email' => $user['email'],
            'rol' => $user['rol']
        ];

        // Redirigir al dashboard
        $this->redirect('dashboard');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        session_destroy();
        $this->redirect('login');
    }
}
