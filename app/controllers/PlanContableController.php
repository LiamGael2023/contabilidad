<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\PlanContable;

class PlanContableController extends Controller
{
    private $planContableModel;

    public function __construct()
    {
        parent::__construct();
        $this->planContableModel = new PlanContable();
    }

    /**
     * Listar cuentas del plan contable
     */
    public function index(Request $request)
    {
        $this->requireAuth();

        // Verificar empresa seleccionada
        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $cuentas = $this->planContableModel->getCuentasByEmpresa($empresaId);

        $this->view('plan-contable.index', [
            'title' => 'Plan Contable',
            'cuentas' => $cuentas
        ]);
    }

    /**
     * Crear cuenta
     */
    public function create(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $this->view('plan-contable.create', [
            'title' => 'Nueva Cuenta Contable',
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Guardar cuenta
     */
    public function store(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('plan-contable');
        }

        try {
            $empresaId = $_SESSION['empresa_id'];

            // Verificar que no exista la cuenta
            $existe = $this->planContableModel->findByCodigo($empresaId, $request->post('codigo'));
            if ($existe) {
                throw new \Exception('Ya existe una cuenta con ese código');
            }

            $data = [
                'empresa_id' => $empresaId,
                'codigo' => $request->post('codigo'),
                'descripcion' => $request->post('descripcion'),
                'elemento' => $request->post('elemento'),
                'nivel' => $request->post('nivel'),
                'codigo_padre' => $request->post('codigo_padre'),
                'naturaleza' => $request->post('naturaleza'),
                'tipo' => $request->post('tipo'),
                'recibe_saldo' => $request->post('recibe_saldo') ? 1 : 0,
                'requiere_auxiliar' => $request->post('requiere_auxiliar') ? 1 : 0,
                'activo' => 1
            ];

            $this->planContableModel->create($data);

            $this->setFlash('success', 'Cuenta creada exitosamente');
            $this->redirect('plan-contable');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('plan-contable/crear');
        }
    }

    /**
     * Cargar PCGE completo
     */
    public function loadPCGE(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->json(['success' => false, 'message' => 'Debe seleccionar una empresa'], 400);
        }

        try {
            $empresaId = $_SESSION['empresa_id'];
            $this->planContableModel->cargarPCGE($empresaId);

            $this->json(['success' => true, 'message' => 'Plan Contable cargado exitosamente']);

        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
