<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\CuentaPorCobrar;
use Models\Pago;
use Models\Tercero;
use Models\PeriodoContable;
use Models\Banco;

class CuentasPorCobrarController extends Controller
{
    private $cxcModel;
    private $pagoModel;
    private $terceroModel;
    private $periodoModel;
    private $bancoModel;

    public function __construct()
    {
        parent::__construct();
        $this->cxcModel = new CuentaPorCobrar();
        $this->pagoModel = new Pago();
        $this->terceroModel = new Tercero();
        $this->periodoModel = new PeriodoContable();
        $this->bancoModel = new Banco();
    }

    /**
     * Listar cuentas por cobrar
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
            $cuentas = $this->cxcModel->getByEmpresa($empresaId);
        } else {
            $cuentas = $this->cxcModel->getByEstado($empresaId, $estado);
        }

        // Totales por estado
        $totales = [
            'pendiente' => $this->cxcModel->getTotalPorEstado($empresaId, 'pendiente'),
            'parcial' => $this->cxcModel->getTotalPorEstado($empresaId, 'parcial'),
            'pagado' => $this->cxcModel->getTotalPorEstado($empresaId, 'pagado'),
            'vencido' => $this->cxcModel->getTotalPorEstado($empresaId, 'vencido')
        ];

        // Cuentas vencidas
        $vencidas = $this->cxcModel->getVencidas($empresaId);
        $porVencer = $this->cxcModel->getPorVencer($empresaId, 30);

        $this->view('cuentas-por-cobrar.index', [
            'title' => 'Cuentas por Cobrar',
            'cuentas' => $cuentas,
            'totales' => $totales,
            'vencidas' => count($vencidas),
            'por_vencer' => count($porVencer),
            'estado_actual' => $estado
        ]);
    }

    /**
     * Crear nueva cuenta por cobrar
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

        $clientes = $this->terceroModel->getClientes($empresaId);

        $this->view('cuentas-por-cobrar.create', [
            'title' => 'Registrar Cuenta por Cobrar',
            'periodo' => $periodo,
            'clientes' => $clientes,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Guardar cuenta por cobrar
     */
    public function store(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('cuentas-por-cobrar');
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
                'registro_venta_id' => $request->post('registro_venta_id') ?: null,
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

            $this->cxcModel->create($data);

            $this->setFlash('success', 'Cuenta por cobrar registrada exitosamente');
            $this->redirect('cuentas-por-cobrar');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('cuentas-por-cobrar/nuevo');
        }
    }

    /**
     * Ver detalle de cuenta por cobrar
     */
    public function show(Request $request, $id)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->redirect('dashboard');
        }

        $cuenta = $this->cxcModel->find($id);

        if (!$cuenta || $cuenta['empresa_id'] != $_SESSION['empresa_id']) {
            $this->setFlash('error', 'Cuenta no encontrada');
            $this->redirect('cuentas-por-cobrar');
        }

        // Obtener pagos realizados
        $pagos = $this->pagoModel->getPagosPorCuenta($id, 'cobrar');

        $this->view('cuentas-por-cobrar.show', [
            'title' => 'Detalle Cuenta por Cobrar',
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

            $cuenta = $this->cxcModel->find($cuentaId);

            if (!$cuenta || $cuenta['empresa_id'] != $_SESSION['empresa_id']) {
                throw new \Exception('Cuenta no encontrada');
            }

            if ($monto <= 0 || $monto > $cuenta['saldo_pendiente']) {
                throw new \Exception('Monto de pago inválido');
            }

            // Registrar el pago
            $pagoId = $this->cxcModel->registrarPago(
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
        $antiguedad = $this->cxcModel->getAntiguedadSaldos($empresaId);

        $this->view('cuentas-por-cobrar.antiguedad', [
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
        $vencidas = $this->cxcModel->getVencidas($empresaId);

        $this->view('cuentas-por-cobrar.vencidas', [
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
        $porVencer = $this->cxcModel->getPorVencer($empresaId, $dias);

        $this->view('cuentas-por-cobrar.por-vencer', [
            'title' => 'Cuentas por Vencer',
            'cuentas' => $porVencer,
            'dias' => $dias
        ]);
    }
}
