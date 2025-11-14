<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\Usuario;

class UsuarioController extends Controller
{
    private $usuarioModel;

    public function __construct()
    {
        parent::__construct();
        $this->usuarioModel = new Usuario();
    }

    /**
     * Listar usuarios
     */
    public function index(Request $request)
    {
        $this->requireAuth();

        $usuarios = $this->usuarioModel->all();

        $this->view('usuarios.index', [
            'title' => 'Usuarios',
            'usuarios' => $usuarios
        ]);
    }

    /**
     * Crear usuario
     */
    public function create(Request $request)
    {
        $this->requireAuth();

        $this->view('usuarios.create', [
            'title' => 'Nuevo Usuario',
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Guardar usuario
     */
    public function store(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost()) {
            $this->redirect('usuarios');
        }

        try {
            $data = [
                'username' => $request->post('username'),
                'password' => $request->post('password'),
                'nombre' => $request->post('nombre'),
                'apellido' => $request->post('apellido'),
                'email' => $request->post('email'),
                'rol' => $request->post('rol'),
                'activo' => 1
            ];

            $this->usuarioModel->createUser($data);

            $this->setFlash('success', 'Usuario creado exitosamente');
            $this->redirect('usuarios');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('usuarios/nuevo');
        }
    }
}
