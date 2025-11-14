<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\PeriodoContable;

class PeriodoController extends Controller
{
    private $periodoModel;

    public function __construct()
    {
        parent::__construct();
        $this->periodoModel = new PeriodoContable();
    }

    /**
     * Listar períodos
     */
    public function index(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $periodos = $this->periodoModel->getByEmpresa($empresaId);

        $this->view('periodos.index', [
            'title' => 'Períodos Contables',
            'periodos' => $periodos,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Crear período
     */
    public function create(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('periodos');
        }

        try {
            $empresaId = $_SESSION['empresa_id'];
            $anio = $request->post('anio');
            $mes = $request->post('mes');

            // Verificar si ya existe
            if ($this->periodoModel->existePeriodo($empresaId, $anio, $mes)) {
                throw new \Exception('El período ya existe');
            }

            $this->periodoModel->crearPeriodo($empresaId, $anio, $mes);

            $this->setFlash('success', 'Período creado exitosamente');
        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
        }

        $this->redirect('periodos');
    }

    /**
     * Cerrar período
     */
    public function cerrar(Request $request, $id)
    {
        $this->requireAuth();

        if (!$request->isPost()) {
            $this->redirect('periodos');
        }

        try {
            $this->periodoModel->cerrar($id, $_SESSION['user_id']);
            $this->setFlash('success', 'Período cerrado exitosamente');
        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
        }

        $this->redirect('periodos');
    }

    /**
     * Reabrir período
     */
    public function reabrir(Request $request, $id)
    {
        $this->requireAuth();

        if (!$request->isPost()) {
            $this->redirect('periodos');
        }

        try {
            $this->periodoModel->reabrir($id);
            $this->setFlash('success', 'Período reabierto exitosamente');
        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
        }

        $this->redirect('periodos');
    }
}
