<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\ComprobantePago;
use Models\Tercero;
use Models\PeriodoContable;

class ComprobanteController extends Controller
{
    private $comprobanteModel;
    private $terceroModel;
    private $periodoModel;

    public function __construct()
    {
        parent::__construct();
        $this->comprobanteModel = new ComprobantePago();
        $this->terceroModel = new Tercero();
        $this->periodoModel = new PeriodoContable();
    }

    /**
     * Listar comprobantes
     */
    public function index(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $periodo = $this->periodoModel->getPeriodoActual($empresaId);

        if (!$periodo) {
            $this->setFlash('error', 'No hay período contable activo');
            $this->redirect('dashboard');
        }

        $comprobantes = $this->comprobanteModel->getByPeriodo($periodo['id']);

        $this->view('comprobantes.index', [
            'title' => 'Comprobantes de Pago',
            'comprobantes' => $comprobantes,
            'periodo' => $periodo
        ]);
    }

    /**
     * Crear comprobante
     */
    public function create(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];

        // Obtener terceros
        $terceros = $this->terceroModel->getByEmpresa($empresaId);

        $this->view('comprobantes.create', [
            'title' => 'Nuevo Comprobante',
            'terceros' => $terceros,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Ver comprobante
     */
    public function show(Request $request, $id)
    {
        $this->requireAuth();

        $comprobante = $this->comprobanteModel->find($id);

        if (!$comprobante) {
            $this->setFlash('error', 'Comprobante no encontrado');
            $this->redirect('comprobantes');
        }

        $this->view('comprobantes.show', [
            'title' => 'Ver Comprobante',
            'comprobante' => $comprobante
        ]);
    }

    /**
     * Anular comprobante
     */
    public function anular(Request $request, $id)
    {
        $this->requireAuth();

        if (!$request->isPost()) {
            $this->redirect('comprobantes');
        }

        try {
            $motivo = $request->post('motivo');
            $this->comprobanteModel->anular($id, $_SESSION['user_id'], $motivo);

            $this->setFlash('success', 'Comprobante anulado exitosamente');
        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
        }

        $this->redirect('comprobantes');
    }
}
