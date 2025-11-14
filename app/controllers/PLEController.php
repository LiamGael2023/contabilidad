<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\PeriodoContable;
use Helpers\PLEGenerator;

class PLEController extends Controller
{
    private $periodoModel;

    public function __construct()
    {
        parent::__construct();
        $this->periodoModel = new PeriodoContable();
    }

    /**
     * Página principal PLE
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

        $this->view('ple.index', [
            'title' => 'PLE - Programa de Libros Electrónicos',
            'periodos' => $periodos,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Generar archivo PLE
     */
    public function generar(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost() || !isset($_SESSION['empresa_id'])) {
            $this->redirect('ple');
        }

        try {
            $empresaId = $_SESSION['empresa_id'];
            $periodoId = $request->post('periodo_id');
            $tipoLibro = $request->post('tipo_libro');

            $periodo = $this->periodoModel->find($periodoId);
            if (!$periodo) {
                throw new \Exception('Período no encontrado');
            }

            // Generar archivo PLE
            $generator = new PLEGenerator();
            $archivo = $generator->generar($empresaId, $periodo, $tipoLibro);

            $this->setFlash('success', "Archivo PLE generado: {$archivo}");
            $this->redirect('ple');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('ple');
        }
    }

    /**
     * Descargar archivo PLE
     */
    public function descargar(Request $request, $archivo)
    {
        $this->requireAuth();

        $rutaArchivo = STORAGE . 'ple' . DS . $archivo;

        if (!file_exists($rutaArchivo)) {
            $this->setFlash('error', 'Archivo no encontrado');
            $this->redirect('ple');
        }

        header('Content-Type: text/plain; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $archivo . '"');
        header('Content-Length: ' . filesize($rutaArchivo));
        readfile($rutaArchivo);
        exit;
    }
}
