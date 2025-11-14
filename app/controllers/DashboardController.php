<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\Empresa;
use Models\AsientoContable;
use Models\ComprobantePago;

class DashboardController extends Controller
{
    private $empresaModel;
    private $asientoModel;
    private $comprobanteModel;

    public function __construct()
    {
        parent::__construct();
        $this->empresaModel = new Empresa();
        $this->asientoModel = new AsientoContable();
        $this->comprobanteModel = new ComprobantePago();
    }

    public function index(Request $request)
    {
        $this->requireAuth();

        $user = $this->currentUser();

        // Obtener empresas activas
        $empresas = $this->empresaModel->getActiveEmpresas();

        // Estadísticas básicas
        $stats = [
            'total_empresas' => count($empresas),
            'asientos_mes' => 0,
            'comprobantes_mes' => 0
        ];

        // Si hay empresa seleccionada, obtener estadísticas
        if (isset($_SESSION['empresa_id'])) {
            $empresaId = $_SESSION['empresa_id'];

            // Obtener período actual
            $periodoModel = new \Models\PeriodoContable();
            $periodo = $periodoModel->getPeriodoActual($empresaId);

            if ($periodo) {
                $stats['asientos_mes'] = $this->asientoModel->count("periodo_id = {$periodo['id']} AND estado = 'registrado'");
                $stats['comprobantes_mes'] = $this->comprobanteModel->count("periodo_id = {$periodo['id']} AND estado != 'anulado'");
            }
        }

        $this->view('dashboard.index', [
            'title' => 'Dashboard',
            'user' => $user,
            'empresas' => $empresas,
            'stats' => $stats
        ]);
    }
}
