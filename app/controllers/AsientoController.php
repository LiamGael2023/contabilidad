<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\AsientoContable;
use Models\PeriodoContable;
use Models\PlanContable;

class AsientoController extends Controller
{
    private $asientoModel;
    private $periodoModel;
    private $planContableModel;

    public function __construct()
    {
        parent::__construct();
        $this->asientoModel = new AsientoContable();
        $this->periodoModel = new PeriodoContable();
        $this->planContableModel = new PlanContable();
    }

    /**
     * Listar asientos contables
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

        $asientos = $this->asientoModel->getByPeriodo($periodo['id']);

        $this->view('asientos.index', [
            'title' => 'Asientos Contables',
            'asientos' => $asientos,
            'periodo' => $periodo
        ]);
    }

    /**
     * Crear nuevo asiento
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

        // Obtener cuentas contables
        $cuentas = $this->planContableModel->getCuentasRecibeSaldo($empresaId);

        // Obtener siguiente número de asiento
        $siguienteNumero = $this->asientoModel->getSiguienteNumero($periodo['id']);

        $this->view('asientos.create', [
            'title' => 'Nuevo Asiento Contable',
            'periodo' => $periodo,
            'cuentas' => $cuentas,
            'siguiente_numero' => $siguienteNumero,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Guardar asiento
     */
    public function store(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('asientos');
        }

        try {
            $empresaId = $_SESSION['empresa_id'];
            $periodo = $this->periodoModel->getPeriodoActual($empresaId);

            if (!$periodo) {
                throw new \Exception('No hay período contable activo');
            }

            if ($periodo['estado'] === 'cerrado') {
                throw new \Exception('El período contable está cerrado');
            }

            // Datos del asiento
            $asientoData = [
                'empresa_id' => $empresaId,
                'periodo_id' => $periodo['id'],
                'numero_asiento' => $request->post('numero_asiento'),
                'fecha' => $request->post('fecha'),
                'tipo_comprobante_id' => $request->post('tipo_comprobante_id') ?: null,
                'serie_comprobante' => $request->post('serie_comprobante') ?: null,
                'numero_comprobante' => $request->post('numero_comprobante') ?: null,
                'glosa' => $request->post('glosa'),
                'tipo_cambio' => $request->post('tipo_cambio') ?: 1.0000,
                'estado' => 'registrado',
                'usuario_id' => $_SESSION['user_id']
            ];

            // Detalles del asiento
            $cuentasId = $request->post('cuenta_id');
            $debes = $request->post('debe');
            $haberes = $request->post('haber');
            $glosas = $request->post('glosa_detalle');

            $detalles = [];
            foreach ($cuentasId as $index => $cuentaId) {
                if (empty($cuentaId)) continue;

                $debe = floatval($debes[$index] ?? 0);
                $haber = floatval($haberes[$index] ?? 0);

                if ($debe == 0 && $haber == 0) continue;

                $detalles[] = [
                    'cuenta_id' => $cuentaId,
                    'glosa' => $glosas[$index] ?? '',
                    'debe' => $debe,
                    'haber' => $haber
                ];
            }

            if (count($detalles) < 2) {
                throw new \Exception('El asiento debe tener al menos 2 líneas');
            }

            // Crear asiento con detalles
            $asientoId = $this->asientoModel->crearConDetalles($asientoData, $detalles);

            $this->setFlash('success', 'Asiento contable registrado exitosamente');
            $this->redirect('asientos');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('asientos/nuevo');
        }
    }

    /**
     * Ver asiento
     */
    public function show(Request $request, $id)
    {
        $this->requireAuth();

        $asiento = $this->asientoModel->getConDetalles($id);

        if (!$asiento) {
            $this->setFlash('error', 'Asiento no encontrado');
            $this->redirect('asientos');
        }

        $this->view('asientos.show', [
            'title' => 'Ver Asiento',
            'asiento' => $asiento
        ]);
    }

    /**
     * Anular asiento
     */
    public function anular(Request $request, $id)
    {
        $this->requireAuth();

        if (!$request->isPost()) {
            $this->redirect('asientos');
        }

        try {
            $motivo = $request->post('motivo');
            if (empty($motivo)) {
                throw new \Exception('Debe indicar el motivo de anulación');
            }

            $this->asientoModel->anular($id, $_SESSION['user_id'], $motivo);

            $this->setFlash('success', 'Asiento anulado exitosamente');
            $this->redirect('asientos');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('asientos');
        }
    }
}
