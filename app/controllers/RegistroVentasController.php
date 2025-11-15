<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\RegistroVenta;
use Models\PeriodoContable;
use Models\Tercero;

class RegistroVentasController extends Controller
{
    private $ventaModel;
    private $periodoModel;
    private $terceroModel;

    public function __construct()
    {
        parent::__construct();
        $this->ventaModel = new RegistroVenta();
        $this->periodoModel = new PeriodoContable();
        $this->terceroModel = new Tercero();
    }

    /**
     * Listar registro de ventas
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

        $ventas = $this->ventaModel->getByPeriodo($periodo['id']);
        $totales = $this->ventaModel->getTotalesPeriodo($periodo['id']);

        $this->view('registro-ventas.index', [
            'title' => 'Registro de Ventas',
            'periodo' => $periodo,
            'ventas' => $ventas,
            'totales' => $totales
        ]);
    }

    /**
     * Crear nueva venta
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
        $siguienteCorrelativo = $this->ventaModel->getSiguienteCorrelativo($periodo['id']);

        $this->view('registro-ventas.create', [
            'title' => 'Registrar Venta',
            'periodo' => $periodo,
            'clientes' => $clientes,
            'siguiente_correlativo' => $siguienteCorrelativo,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Guardar venta
     */
    public function store(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('registro-ventas');
        }

        try {
            $empresaId = $_SESSION['empresa_id'];
            $periodo = $this->periodoModel->getPeriodoActual($empresaId);

            if (!$periodo || $periodo['estado'] === 'cerrado') {
                throw new \Exception('El período contable está cerrado');
            }

            $data = [
                'empresa_id' => $empresaId,
                'periodo_id' => $periodo['id'],
                'correlativo' => $request->post('correlativo'),
                'fecha_emision' => $request->post('fecha_emision'),
                'fecha_vencimiento' => $request->post('fecha_vencimiento') ?: null,
                'tipo_comprobante' => $request->post('tipo_comprobante'),
                'serie' => $request->post('serie'),
                'numero' => $request->post('numero'),
                'tipo_documento_cliente' => $request->post('tipo_documento_cliente'),
                'numero_documento_cliente' => $request->post('numero_documento_cliente'),
                'razon_social_cliente' => $request->post('razon_social_cliente'),
                'valor_exportacion' => floatval($request->post('valor_exportacion') ?? 0),
                'base_imponible' => floatval($request->post('base_imponible') ?? 0),
                'igv' => floatval($request->post('igv') ?? 0),
                'exonerado' => floatval($request->post('exonerado') ?? 0),
                'inafecto' => floatval($request->post('inafecto') ?? 0),
                'isc' => floatval($request->post('isc') ?? 0),
                'otros_tributos' => floatval($request->post('otros_tributos') ?? 0),
                'importe_total' => floatval($request->post('importe_total')),
                'tipo_cambio' => floatval($request->post('tipo_cambio') ?? 1.0000),
                'moneda' => $request->post('moneda') ?? 'PEN',
                'estado' => 'registrado'
            ];

            $this->ventaModel->create($data);

            $this->setFlash('success', 'Venta registrada exitosamente');
            $this->redirect('registro-ventas');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('registro-ventas/nuevo');
        }
    }

    /**
     * Exportar PLE 14.1
     */
    public function exportarPLE(Request $request)
    {
        $this->requireAuth();

        if (!isset($_SESSION['empresa_id'])) {
            $this->json(['success' => false, 'message' => 'Debe seleccionar una empresa'], 400);
        }

        try {
            $empresaId = $_SESSION['empresa_id'];
            $periodo = $this->periodoModel->getPeriodoActual($empresaId);

            if (!$periodo) {
                throw new \Exception('No hay período contable activo');
            }

            $contenido = $this->ventaModel->generarPLE($periodo['id'], $empresaId, $periodo);

            // Nombre del archivo según nomenclatura SUNAT
            $ruc = $_SESSION['empresa_ruc'] ?? '00000000000';
            $anio = $periodo['anio'];
            $mes = str_pad($periodo['mes'], 2, '0', STR_PAD_LEFT);
            $nombreArchivo = "LE{$ruc}{$anio}{$mes}00140100001111.txt";

            header('Content-Type: text/plain');
            header("Content-Disposition: attachment; filename=\"{$nombreArchivo}\"");
            echo $contenido;
            exit;

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('registro-ventas');
        }
    }
}
