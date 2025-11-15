<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\Banco;
use Models\MovimientoCaja;

class BancoController extends Controller
{
    private $bancoModel;
    private $movimientoModel;

    public function __construct()
    {
        parent::__construct();
        $this->bancoModel = new Banco();
        $this->movimientoModel = new MovimientoCaja();
    }

    /**
     * Listar bancos y cajas
     */
    public function index(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $bancos = $this->bancoModel->getByEmpresa($empresaId);

        // Calcular totales
        $totalPEN = 0;
        $totalUSD = 0;

        foreach ($bancos as $banco) {
            if ($banco['activo']) {
                if ($banco['moneda'] === 'PEN') {
                    $totalPEN += $banco['saldo_actual'];
                } elseif ($banco['moneda'] === 'USD') {
                    $totalUSD += $banco['saldo_actual'];
                }
            }
        }

        $this->view('bancos.index', [
            'title' => 'Caja y Bancos',
            'bancos' => $bancos,
            'total_pen' => $totalPEN,
            'total_usd' => $totalUSD
        ]);
    }

    /**
     * Crear nuevo banco/caja
     */
    public function create(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $this->view('bancos.create', [
            'title' => 'Registrar Cuenta Bancaria/Caja',
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Guardar banco/caja
     */
    public function store(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('bancos');
        }

        try {
            $empresaId = $_SESSION['empresa_id'];
            $saldoInicial = floatval($request->post('saldo_inicial') ?? 0);

            $data = [
                'empresa_id' => $empresaId,
                'nombre_banco' => $request->post('nombre_banco'),
                'tipo_cuenta' => $request->post('tipo_cuenta'),
                'numero_cuenta' => $request->post('numero_cuenta') ?: null,
                'moneda' => $request->post('moneda') ?? 'PEN',
                'saldo_inicial' => $saldoInicial,
                'saldo_actual' => $saldoInicial,
                'activo' => 1
            ];

            $this->bancoModel->create($data);

            $this->setFlash('success', 'Cuenta registrada exitosamente');
            $this->redirect('bancos');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('bancos/nuevo');
        }
    }

    /**
     * Editar banco/caja
     */
    public function edit(Request $request, $id)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->redirect('dashboard');
        }

        $banco = $this->bancoModel->find($id);

        if (!$banco || $banco['empresa_id'] != $_SESSION['empresa_id']) {
            $this->setFlash('error', 'Cuenta no encontrada');
            $this->redirect('bancos');
        }

        $this->view('bancos.edit', [
            'title' => 'Editar Cuenta',
            'banco' => $banco,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Actualizar banco/caja
     */
    public function update(Request $request, $id)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('bancos');
        }

        try {
            $banco = $this->bancoModel->find($id);

            if (!$banco || $banco['empresa_id'] != $_SESSION['empresa_id']) {
                throw new \Exception('Cuenta no encontrada');
            }

            $data = [
                'nombre_banco' => $request->post('nombre_banco'),
                'tipo_cuenta' => $request->post('tipo_cuenta'),
                'numero_cuenta' => $request->post('numero_cuenta') ?: null,
                'activo' => $request->post('activo') ?? 0
            ];

            $this->bancoModel->update($id, $data);

            $this->setFlash('success', 'Cuenta actualizada exitosamente');
            $this->redirect('bancos');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect("bancos/editar/{$id}");
        }
    }

    /**
     * Activar/Desactivar cuenta
     */
    public function toggleActivo(Request $request, $id)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        try {
            $banco = $this->bancoModel->find($id);

            if (!$banco || $banco['empresa_id'] != $_SESSION['empresa_id']) {
                throw new \Exception('Cuenta no encontrada');
            }

            $nuevoEstado = $banco['activo'] ? 0 : 1;
            $this->bancoModel->update($id, ['activo' => $nuevoEstado]);

            $this->json([
                'success' => true,
                'activo' => $nuevoEstado,
                'message' => $nuevoEstado ? 'Cuenta activada' : 'Cuenta desactivada'
            ]);

        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Ver movimientos de una cuenta
     */
    public function movimientos(Request $request, $id)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->redirect('dashboard');
        }

        $banco = $this->bancoModel->find($id);

        if (!$banco || $banco['empresa_id'] != $_SESSION['empresa_id']) {
            $this->setFlash('error', 'Cuenta no encontrada');
            $this->redirect('bancos');
        }

        // Filtros de fecha
        $fechaInicio = $request->get('fecha_inicio', date('Y-m-01'));
        $fechaFin = $request->get('fecha_fin', date('Y-m-d'));

        $movimientos = $this->movimientoModel->getByBanco($id, $fechaInicio, $fechaFin);

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

        $this->view('bancos.movimientos', [
            'title' => 'Movimientos - ' . $banco['nombre_banco'],
            'banco' => $banco,
            'movimientos' => $movimientos,
            'total_ingresos' => $totalIngresos,
            'total_egresos' => $totalEgresos,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);
    }

    /**
     * Resumen de liquidez
     */
    public function liquidez(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->setFlash('error', 'Debe seleccionar una empresa');
            $this->redirect('dashboard');
        }

        $empresaId = $_SESSION['empresa_id'];
        $resumen = $this->bancoModel->getResumenLiquidez($empresaId);

        $this->view('bancos.liquidez', [
            'title' => 'Resumen de Liquidez',
            'resumen' => $resumen
        ]);
    }
}
