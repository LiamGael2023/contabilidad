<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\AsientoContable;
use Models\PlanContable;
use Models\DetalleAsiento;
use Models\PeriodoContable;

class LibroController extends Controller
{
    private $asientoModel;
    private $planContableModel;
    private $detalleModel;
    private $periodoModel;

    public function __construct()
    {
        parent::__construct();
        $this->asientoModel = new AsientoContable();
        $this->planContableModel = new PlanContable();
        $this->detalleModel = new DetalleAsiento();
        $this->periodoModel = new PeriodoContable();
    }

    /**
     * Libro Diario
     */
    public function diario(Request $request)
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

        // Obtener asientos del período
        $asientos = $this->asientoModel->getByPeriodo($periodo['id']);

        // Obtener detalles de cada asiento
        foreach ($asientos as &$asiento) {
            $asiento['detalles'] = $this->detalleModel->getByAsiento($asiento['id']);
        }

        $this->view('libros.diario', [
            'title' => 'Libro Diario',
            'periodo' => $periodo,
            'asientos' => $asientos
        ]);
    }

    /**
     * Libro Mayor
     */
    public function mayor(Request $request)
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

        // Obtener todas las cuentas
        $cuentas = $this->planContableModel->getCuentasRecibeSaldo($empresaId);

        $this->view('libros.mayor', [
            'title' => 'Libro Mayor',
            'periodo' => $periodo,
            'cuentas' => $cuentas
        ]);
    }

    /**
     * Detalle del Libro Mayor por cuenta
     */
    public function mayorDetalle(Request $request, $cuentaId)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->json(['error' => 'Debe seleccionar una empresa'], 400);
        }

        $empresaId = $_SESSION['empresa_id'];
        $periodo = $this->periodoModel->getPeriodoActual($empresaId);

        if (!$periodo) {
            $this->json(['error' => 'No hay período contable activo'], 400);
        }

        // Obtener cuenta
        $cuenta = $this->planContableModel->find($cuentaId);

        // Obtener movimientos
        $movimientos = $this->detalleModel->getByCuenta($cuentaId, $periodo['id']);

        // Calcular saldos acumulados
        $saldo = 0;
        foreach ($movimientos as &$mov) {
            if ($cuenta['naturaleza'] === 'DEUDORA') {
                $saldo += ($mov['debe'] - $mov['haber']);
            } else {
                $saldo += ($mov['haber'] - $mov['debe']);
            }
            $mov['saldo'] = $saldo;
        }

        $this->view('libros.mayor-detalle', [
            'title' => "Libro Mayor - {$cuenta['codigo']} {$cuenta['descripcion']}",
            'cuenta' => $cuenta,
            'movimientos' => $movimientos,
            'periodo' => $periodo
        ]);
    }

    /**
     * Libro Caja y Bancos
     */
    public function cajaBancos(Request $request)
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

        // Obtener cuentas de efectivo (elemento 1, cuentas 10)
        $sql = "SELECT * FROM plan_contable
                WHERE empresa_id = :empresa_id
                AND codigo LIKE '10%'
                AND recibe_saldo = 1
                AND activo = 1
                ORDER BY codigo";
        $cuentas = $this->planContableModel->query($sql, ['empresa_id' => $empresaId])->fetchAll();

        // Obtener movimientos de cada cuenta
        foreach ($cuentas as &$cuenta) {
            $cuenta['movimientos'] = $this->detalleModel->getByCuenta($cuenta['id'], $periodo['id']);
        }

        $this->view('libros.caja-bancos', [
            'title' => 'Libro Caja y Bancos',
            'periodo' => $periodo,
            'cuentas' => $cuentas
        ]);
    }
}
