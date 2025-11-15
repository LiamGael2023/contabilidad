<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\MovimientoCaja;
use Models\Banco;
use Models\Tercero;
use Models\TipoCambio;

class MovimientoCajaController extends Controller
{
    private $movimientoModel;
    private $bancoModel;
    private $terceroModel;
    private $tipoCambioModel;

    public function __construct()
    {
        parent::__construct();
        $this->movimientoModel = new MovimientoCaja();
        $this->bancoModel = new Banco();
        $this->terceroModel = new Tercero();
        $this->tipoCambioModel = new TipoCambio();
    }

    /**
     * Listar movimientos de caja
     */
    public function index(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];

        // Filtros
        $bancoId = $request->get('banco_id', 'todos');
        $fechaInicio = $request->get('fecha_inicio', date('Y-m-01'));
        $fechaFin = $request->get('fecha_fin', date('Y-m-d'));

        // Obtener movimientos
        if ($bancoId === 'todos') {
            $movimientos = $this->movimientoModel->getByEmpresa($empresaId, $fechaInicio, $fechaFin);
        } else {
            $movimientos = $this->movimientoModel->getByBanco($bancoId, $fechaInicio, $fechaFin);
        }

        // Calcular totales
        $totalIngresos = 0;
        $totalEgresos = 0;

        foreach ($movimientos as $mov) {
            if ($mov['tipo_movimiento'] === 'ingreso') {
                $totalIngresos += $mov['monto'];
            } else {
                $totalEgresos += $mov['monto'];
            }
        }

        $bancos = $this->bancoModel->getByEmpresa($empresaId);

        $this->view('movimientos-caja.index', [
            'title' => 'Movimientos de Caja',
            'movimientos' => $movimientos,
            'bancos' => $bancos,
            'total_ingresos' => $totalIngresos,
            'total_egresos' => $totalEgresos,
            'banco_id' => $bancoId,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);
    }

    /**
     * Registrar ingreso
     */
    public function ingreso(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $bancos = $this->bancoModel->getActivos($empresaId);
        $terceros = $this->terceroModel->getByEmpresa($empresaId);
        $tipoCambioActual = $this->tipoCambioModel->getActual('USD');

        $this->view('movimientos-caja.ingreso', [
            'title' => 'Registrar Ingreso',
            'bancos' => $bancos,
            'terceros' => $terceros,
            'tipo_cambio' => $tipoCambioActual ? $tipoCambioActual['venta'] : 1.0000,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Guardar ingreso
     */
    public function storeIngreso(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('movimientos-caja');
        }

        try {
            $empresaId = $_SESSION['empresa_id'];

            $data = [
                'empresa_id' => $empresaId,
                'banco_id' => $request->post('banco_id'),
                'fecha' => $request->post('fecha'),
                'tipo_operacion' => $request->post('tipo_operacion'),
                'monto' => floatval($request->post('monto')),
                'moneda' => $request->post('moneda') ?? 'PEN',
                'tipo_cambio' => floatval($request->post('tipo_cambio') ?? 1.0000),
                'descripcion' => $request->post('descripcion'),
                'numero_operacion' => $request->post('numero_operacion') ?: null,
                'tercero_id' => $request->post('tercero_id') ?: null,
                'categoria' => $request->post('categoria') ?: null
            ];

            $this->movimientoModel->registrarIngreso($data);

            $this->setFlash('success', 'Ingreso registrado exitosamente');
            $this->redirect('movimientos-caja');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('movimientos-caja/ingreso');
        }
    }

    /**
     * Registrar egreso
     */
    public function egreso(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $bancos = $this->bancoModel->getActivos($empresaId);
        $terceros = $this->terceroModel->getByEmpresa($empresaId);
        $tipoCambioActual = $this->tipoCambioModel->getActual('USD');

        $this->view('movimientos-caja.egreso', [
            'title' => 'Registrar Egreso',
            'bancos' => $bancos,
            'terceros' => $terceros,
            'tipo_cambio' => $tipoCambioActual ? $tipoCambioActual['compra'] : 1.0000,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Guardar egreso
     */
    public function storeEgreso(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('movimientos-caja');
        }

        try {
            $empresaId = $_SESSION['empresa_id'];

            $data = [
                'empresa_id' => $empresaId,
                'banco_id' => $request->post('banco_id'),
                'fecha' => $request->post('fecha'),
                'tipo_operacion' => $request->post('tipo_operacion'),
                'monto' => floatval($request->post('monto')),
                'moneda' => $request->post('moneda') ?? 'PEN',
                'tipo_cambio' => floatval($request->post('tipo_cambio') ?? 1.0000),
                'descripcion' => $request->post('descripcion'),
                'numero_operacion' => $request->post('numero_operacion') ?: null,
                'tercero_id' => $request->post('tercero_id') ?: null,
                'categoria' => $request->post('categoria') ?: null
            ];

            $this->movimientoModel->registrarEgreso($data);

            $this->setFlash('success', 'Egreso registrado exitosamente');
            $this->redirect('movimientos-caja');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('movimientos-caja/egreso');
        }
    }

    /**
     * Flujo de caja diario
     */
    public function flujoDiario(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $fecha = $request->get('fecha', date('Y-m-d'));

        $flujo = $this->movimientoModel->getFlujoDiario($empresaId, $fecha);

        $this->view('movimientos-caja.flujo-diario', [
            'title' => 'Flujo de Caja Diario',
            'flujo' => $flujo,
            'fecha' => $fecha
        ]);
    }

    /**
     * Flujo de caja mensual
     */
    public function flujoMensual(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $anio = $request->get('anio', date('Y'));
        $mes = $request->get('mes', date('m'));

        $flujo = $this->movimientoModel->getFlujoMensual($empresaId, $anio, $mes);

        $this->view('movimientos-caja.flujo-mensual', [
            'title' => 'Flujo de Caja Mensual',
            'flujo' => $flujo,
            'anio' => $anio,
            'mes' => $mes
        ]);
    }

    /**
     * Eliminar movimiento
     */
    public function delete(Request $request, $id)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        try {
            $movimiento = $this->movimientoModel->find($id);

            if (!$movimiento || $movimiento['empresa_id'] != $_SESSION['empresa_id']) {
                throw new \Exception('Movimiento no encontrado');
            }

            // Revertir el saldo del banco
            $tipo = $movimiento['tipo_movimiento'] === 'ingreso' ? 'egreso' : 'ingreso';
            $this->bancoModel->actualizarSaldo($movimiento['banco_id'], $movimiento['monto'], $tipo);

            // Eliminar el movimiento
            $this->movimientoModel->delete($id);

            $this->setFlash('success', 'Movimiento eliminado exitosamente');
            $this->json(['success' => true]);

        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
