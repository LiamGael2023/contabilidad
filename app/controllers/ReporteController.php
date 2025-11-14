<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\PlanContable;
use Models\PeriodoContable;
use Models\DetalleAsiento;
use Helpers\ReporteHelper;

class ReporteController extends Controller
{
    private $planContableModel;
    private $periodoModel;
    private $detalleModel;

    public function __construct()
    {
        parent::__construct();
        $this->planContableModel = new PlanContable();
        $this->periodoModel = new PeriodoContable();
        $this->detalleModel = new DetalleAsiento();
    }

    /**
     * Balance General
     */
    public function balanceGeneral(Request $request)
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

        // Obtener cuentas de balance (Activo, Pasivo, Patrimonio)
        $helper = new ReporteHelper();
        $balance = $helper->generarBalanceGeneral($empresaId, $periodo['id']);

        $this->view('reportes.balance-general', [
            'title' => 'Balance General',
            'periodo' => $periodo,
            'balance' => $balance
        ]);
    }

    /**
     * Estado de Resultados
     */
    public function estadoResultados(Request $request)
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

        // Obtener cuentas de resultados (Ingresos y Gastos)
        $helper = new ReporteHelper();
        $estadoResultados = $helper->generarEstadoResultados($empresaId, $periodo['id']);

        $this->view('reportes.estado-resultados', [
            'title' => 'Estado de Resultados',
            'periodo' => $periodo,
            'estado' => $estadoResultados
        ]);
    }

    /**
     * Flujo de Efectivo
     */
    public function flujoEfectivo(Request $request)
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

        $helper = new ReporteHelper();
        $flujo = $helper->generarFlujoEfectivo($empresaId, $periodo['id']);

        $this->view('reportes.flujo-efectivo', [
            'title' => 'Flujo de Efectivo',
            'periodo' => $periodo,
            'flujo' => $flujo
        ]);
    }

    /**
     * Estado de Cambios en el Patrimonio
     */
    public function estadoCambiosPatrimonio(Request $request)
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

        $this->view('reportes.estado-cambios-patrimonio', [
            'title' => 'Estado de Cambios en el Patrimonio',
            'periodo' => $periodo
        ]);
    }
}
