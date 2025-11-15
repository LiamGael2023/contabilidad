<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\CuentaPorPagar;
use Models\Pago;
use Models\Tercero;
use Models\PeriodoContable;
use Models\Banco;

class CuentasPorPagarController extends Controller
{
    private $cxpModel;
    private $pagoModel;
    private $terceroModel;
    private $periodoModel;
    private $bancoModel;

    public function __construct()
    {
        parent::__construct();
        $this->cxpModel = new CuentaPorPagar();
        $this->pagoModel = new Pago();
        $this->terceroModel = new Tercero();
        $this->periodoModel = new PeriodoContable();
        $this->bancoModel = new Banco();
    }

    /**
     * Listar cuentas por pagar
     */
    public function index(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $estado = $request->get('estado', 'pendiente');

        // Obtener cuentas según estado
        if ($estado === 'todos') {
            $cuentas = $this->cxpModel->getByEmpresa($empresaId);
        } else {
            $cuentas = $this->cxpModel->getByEstado($empresaId, $estado);
        }

        // Totales por estado
        $totales = [
            'pendiente' => $this->cxpModel->getTotalPorEstado($empresaId, 'pendiente'),
            'parcial' => $this->cxpModel->getTotalPorEstado($empresaId, 'parcial'),
            'pagado' => $this->cxpModel->getTotalPorEstado($empresaId, 'pagado'),
            'vencido' => $this->cxpModel->getTotalPorEstado($empresaId, 'vencido')
        ];

        // Cuentas vencidas
        $vencidas = $this->cxpModel->getVencidas($empresaId);
        $porVencer = $this->cxpModel->getPorVencer($empresaId, 30);

        $this->view('cuentas-por-pagar.index', [
            'title' => 'Cuentas por Pagar',
            'cuentas' => $cuentas,
            'totales' => $totales,
            'vencidas' => count($vencidas),
            'por_vencer' => count($porVencer),
            'estado_actual' => $estado
        ]);
    }

    /**
     * Crear nueva cuenta por pagar
     */
    public function create(Request $request)
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

        $proveedores = $this->terceroModel->getProveedores($empresaId);

        $this->view('cuentas-por-pagar.create', [
            'title' => 'Registrar Cuenta por Pagar',
            'periodo' => $periodo,
            'proveedores' => $proveedores,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Guardar cuenta por pagar
     */
    public function store(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('cuentas-por-pagar');
        }

        try {
            $empresaId = $_SESSION['empresa_id'];
            $periodo = $this->periodoModel->getPeriodoActual($empresaId);

            if (!$periodo || $periodo['estado'] === 'cerrado') {
                throw new \Exception('El período contable está cerrado');
            }

            $importeTotal = floatval($request->post('importe_total'));

            $data = [
                'empresa_id' => $empresaId,
                'periodo_id' => $periodo['id'],
                'tercero_id' => $request->post('tercero_id'),
                'registro_compra_id' => $request->post('registro_compra_id') ?: null,
                'tipo_documento' => $request->post('tipo_documento'),
                'serie' => $request->post('serie'),
                'numero' => $request->post('numero'),
                'fecha_emision' => $request->post('fecha_emision'),
                'fecha_vencimiento' => $request->post('fecha_vencimiento'),
                'moneda' => $request->post('moneda') ?? 'PEN',
                'tipo_cambio' => floatval($request->post('tipo_cambio') ?? 1.0000),
                'importe_total' => $importeTotal,
                'saldo_pendiente' => $importeTotal,
                'estado' => 'pendiente',
                'observaciones' => $request->post('observaciones') ?: null
            ];

            $this->cxpModel->create($data);

            $this->setFlash('success', 'Cuenta por pagar registrada exitosamente');
            $this->redirect('cuentas-por-pagar');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('cuentas-por-pagar/nuevo');
        }
    }

    /**
     * Ver detalle de cuenta por pagar
     */
    public function show(Request $request, $id)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->redirect('dashboard');
        }

        $cuenta = $this->cxpModel->find($id);

        if (!$cuenta || $cuenta['empresa_id'] != $_SESSION['empresa_id']) {
            $this->setFlash('error', 'Cuenta no encontrada');
            $this->redirect('cuentas-por-pagar');
        }

        // Obtener pagos realizados
        $pagos = $this->pagoModel->getPagosPorCuenta($id, 'pagar');

        $this->view('cuentas-por-pagar.show', [
            'title' => 'Detalle Cuenta por Pagar',
            'cuenta' => $cuenta,
            'pagos' => $pagos,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Registrar pago
     */
    public function registrarPago(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->json(['success' => false, 'message' => 'Solicitud inválida'], 400);
        }

        try {
            $cuentaId = $request->post('cuenta_id');
            $monto = floatval($request->post('monto'));
            $fechaPago = $request->post('fecha_pago');
            $metodoPago = $request->post('metodo_pago');
            $numeroOperacion = $request->post('numero_operacion') ?: null;
            $bancoId = $request->post('banco_id') ?: null;
            $observaciones = $request->post('observaciones') ?: null;

            $cuenta = $this->cxpModel->find($cuentaId);

            if (!$cuenta || $cuenta['empresa_id'] != $_SESSION['empresa_id']) {
                throw new \Exception('Cuenta no encontrada');
            }

            if ($monto <= 0 || $monto > $cuenta['saldo_pendiente']) {
                throw new \Exception('Monto de pago inválido');
            }

            // Registrar el pago
            $pagoId = $this->cxpModel->registrarPago(
                $cuentaId,
                $monto,
                $fechaPago,
                $metodoPago,
                $numeroOperacion,
                $bancoId,
                $observaciones
            );

            $this->setFlash('success', 'Pago registrado exitosamente');
            $this->json(['success' => true, 'pago_id' => $pagoId]);

        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Reporte de antigüedad de saldos
     */
    public function antiguedadSaldos(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $antiguedad = $this->cxpModel->getAntiguedadSaldos($empresaId);

        $this->view('cuentas-por-pagar.antiguedad', [
            'title' => 'Antigüedad de Saldos',
            'antiguedad' => $antiguedad
        ]);
    }

    /**
     * Reporte de cuentas vencidas
     */
    public function vencidas(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $vencidas = $this->cxpModel->getVencidas($empresaId);

        $this->view('cuentas-por-pagar.vencidas', [
            'title' => 'Cuentas Vencidas',
            'cuentas' => $vencidas
        ]);
    }

    /**
     * Reporte de cuentas por vencer
     */
    public function porVencer(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $dias = $request->get('dias', 30);
        $porVencer = $this->cxpModel->getPorVencer($empresaId, $dias);

        $this->view('cuentas-por-pagar.por-vencer', [
            'title' => 'Cuentas por Vencer',
            'cuentas' => $porVencer,
            'dias' => $dias
        ]);
    }
}
