<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\Tercero;

class TerceroController extends Controller
{
    private $terceroModel;

    public function __construct()
    {
        parent::__construct();
        $this->terceroModel = new Tercero();
    }

    /**
     * Listar terceros
     */
    public function index(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $tipo = $request->get('tipo'); // cliente, proveedor, ambos

        if ($tipo === 'cliente') {
            $terceros = $this->terceroModel->getClientes($empresaId);
            $titulo = 'Clientes';
        } elseif ($tipo === 'proveedor') {
            $terceros = $this->terceroModel->getProveedores($empresaId);
            $titulo = 'Proveedores';
        } else {
            $terceros = $this->terceroModel->getByEmpresa($empresaId);
            $titulo = 'Terceros (Clientes y Proveedores)';
        }

        $this->view('terceros.index', [
            'title' => $titulo,
            'terceros' => $terceros,
            'tipo_filtro' => $tipo
        ]);
    }

    /**
     * Mostrar formulario de nuevo tercero
     */
    public function create(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $this->view('terceros.create', [
            'title' => 'Nuevo Tercero',
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Guardar nuevo tercero
     */
    public function store(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('terceros');
        }

        try {
            $empresaId = $_SESSION['empresa_id'];
            $tipoDocumento = $request->post('tipo_documento');
            $numeroDocumento = $request->post('numero_documento');

            // Validar tipo y número de documento
            if ($tipoDocumento == '6' && strlen($numeroDocumento) != 11) {
                throw new \Exception('El RUC debe tener 11 dígitos');
            }
            if ($tipoDocumento == '1' && strlen($numeroDocumento) != 8) {
                throw new \Exception('El DNI debe tener 8 dígitos');
            }

            // Verificar si ya existe
            $existe = $this->terceroModel->findByDocumento($empresaId, $tipoDocumento, $numeroDocumento);
            if ($existe) {
                throw new \Exception('Ya existe un tercero con ese tipo y número de documento');
            }

            $data = [
                'empresa_id' => $empresaId,
                'tipo_documento' => $tipoDocumento,
                'numero_documento' => $numeroDocumento,
                'razon_social' => $request->post('razon_social'),
                'nombre_comercial' => $request->post('nombre_comercial'),
                'direccion' => $request->post('direccion'),
                'ubigeo' => $request->post('ubigeo'),
                'telefono' => $request->post('telefono'),
                'email' => $request->post('email'),
                'tipo_tercero' => $request->post('tipo_tercero'),
                'activo' => 1
            ];

            $this->terceroModel->create($data);

            $this->setFlash('success', 'Tercero registrado exitosamente');
            $this->redirect('terceros');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('terceros/nuevo');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Request $request, $id)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $tercero = $this->terceroModel->find($id);

        if (!$tercero || $tercero['empresa_id'] != $_SESSION['empresa_id']) {
            $this->setFlash('error', 'Tercero no encontrado');
            $this->redirect('terceros');
        }

        $this->view('terceros.edit', [
            'title' => 'Editar Tercero',
            'tercero' => $tercero,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Actualizar tercero
     */
    public function update(Request $request, $id)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('terceros');
        }

        try {
            $tercero = $this->terceroModel->find($id);

            if (!$tercero || $tercero['empresa_id'] != $_SESSION['empresa_id']) {
                throw new \Exception('Tercero no encontrado');
            }

            $data = [
                'razon_social' => $request->post('razon_social'),
                'nombre_comercial' => $request->post('nombre_comercial'),
                'direccion' => $request->post('direccion'),
                'ubigeo' => $request->post('ubigeo'),
                'telefono' => $request->post('telefono'),
                'email' => $request->post('email'),
                'tipo_tercero' => $request->post('tipo_tercero')
            ];

            $this->terceroModel->update($id, $data);

            $this->setFlash('success', 'Tercero actualizado exitosamente');
            $this->redirect('terceros');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect("terceros/editar/{$id}");
        }
    }

    /**
     * Eliminar tercero
     */
    public function delete(Request $request, $id)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('terceros');
        }

        try {
            $tercero = $this->terceroModel->find($id);

            if (!$tercero || $tercero['empresa_id'] != $_SESSION['empresa_id']) {
                throw new \Exception('Tercero no encontrado');
            }

            // Desactivar en lugar de eliminar
            $this->terceroModel->update($id, ['activo' => 0]);

            $this->setFlash('success', 'Tercero eliminado exitosamente');
        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
        }

        $this->redirect('terceros');
    }
}
