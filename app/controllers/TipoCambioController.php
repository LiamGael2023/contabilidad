<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\TipoCambio;

class TipoCambioController extends Controller
{
    private $tipoCambioModel;

    public function __construct()
    {
        parent::__construct();
        $this->tipoCambioModel = new TipoCambio();
    }

    /**
     * Listar tipos de cambio
     */
    public function index(Request $request)
    {
        $this->requireAuth();

        // Filtros
        $fechaInicio = $request->get('fecha_inicio', date('Y-m-01'));
        $fechaFin = $request->get('fecha_fin', date('Y-m-d'));
        $moneda = $request->get('moneda', 'USD');

        $tiposCambio = $this->tipoCambioModel->getByRango($fechaInicio, $fechaFin, $moneda);
        $tipoCambioActual = $this->tipoCambioModel->getActual($moneda);

        $this->view('tipos-cambio.index', [
            'title' => 'Tipos de Cambio',
            'tipos_cambio' => $tiposCambio,
            'tipo_cambio_actual' => $tipoCambioActual,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'moneda' => $moneda
        ]);
    }

    /**
     * Registrar tipo de cambio
     */
    public function create(Request $request)
    {
        $this->requireAuth();

        $this->view('tipos-cambio.create', [
            'title' => 'Registrar Tipo de Cambio',
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Guardar tipo de cambio
     */
    public function store(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost()) {
            $this->redirect('tipos-cambio');
        }

        try {
            $fecha = $request->post('fecha');
            $moneda = $request->post('moneda') ?? 'USD';
            $compra = floatval($request->post('compra'));
            $venta = floatval($request->post('venta'));
            $fuente = $request->post('fuente') ?? 'Manual';

            if ($compra <= 0 || $venta <= 0) {
                throw new \Exception('Los valores de compra y venta deben ser mayores a 0');
            }

            if ($venta < $compra) {
                throw new \Exception('El tipo de cambio de venta no puede ser menor al de compra');
            }

            $this->tipoCambioModel->registrar($fecha, $compra, $venta, $moneda, $fuente);

            $this->setFlash('success', 'Tipo de cambio registrado exitosamente');
            $this->redirect('tipos-cambio');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('tipos-cambio/nuevo');
        }
    }

    /**
     * Importar desde SUNAT
     */
    public function importarSUNAT(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost()) {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }

        try {
            $fecha = $request->post('fecha');

            if (!$fecha) {
                throw new \Exception('Debe especificar una fecha');
            }

            // TODO: Implementar integración con API de SUNAT
            // Por ahora retornamos un mensaje indicando que debe implementarse
            throw new \Exception('La integración con SUNAT aún no está implementada. Por favor, registre el tipo de cambio manualmente.');

            // Cuando se implemente, el código debería ser similar a:
            /*
            $resultado = $this->tipoCambioModel->importarDesdeSUNAT($fecha);

            if ($resultado) {
                $this->json([
                    'success' => true,
                    'message' => 'Tipo de cambio importado exitosamente',
                    'data' => $resultado
                ]);
            } else {
                throw new \Exception('No se pudo obtener el tipo de cambio de SUNAT');
            }
            */

        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Obtener tipo de cambio por fecha (AJAX)
     */
    public function getPorFecha(Request $request)
    {
        $this->requireAuth();

        try {
            $fecha = $request->get('fecha');
            $moneda = $request->get('moneda', 'USD');

            if (!$fecha) {
                throw new \Exception('Debe especificar una fecha');
            }

            $tipoCambio = $this->tipoCambioModel->getByFecha($fecha, $moneda);

            if ($tipoCambio) {
                $this->json([
                    'success' => true,
                    'data' => $tipoCambio
                ]);
            } else {
                // Si no existe, intentar obtener el más cercano anterior
                $tipoCambio = $this->tipoCambioModel->getActual($moneda);

                if ($tipoCambio) {
                    $this->json([
                        'success' => true,
                        'data' => $tipoCambio,
                        'warning' => 'No hay tipo de cambio para esa fecha. Se muestra el último registrado.'
                    ]);
                } else {
                    throw new \Exception('No hay tipos de cambio registrados');
                }
            }

        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Promedios mensuales
     */
    public function promedioMensual(Request $request)
    {
        $this->requireAuth();

        $anio = $request->get('anio', date('Y'));
        $mes = $request->get('mes', date('m'));
        $moneda = $request->get('moneda', 'USD');

        $promedio = $this->tipoCambioModel->getPromedioMes($anio, $mes, $moneda);

        $this->view('tipos-cambio.promedio', [
            'title' => 'Promedio Mensual',
            'promedio' => $promedio,
            'anio' => $anio,
            'mes' => $mes,
            'moneda' => $moneda
        ]);
    }

    /**
     * Tipo de cambio para cierre de mes
     */
    public function tipoCambioCierre(Request $request)
    {
        $this->requireAuth();

        $anio = $request->get('anio', date('Y'));
        $mes = $request->get('mes', date('m'));
        $moneda = $request->get('moneda', 'USD');

        $tipoCambioCierre = $this->tipoCambioModel->getTipoCambioCierre($anio, $mes, $moneda);

        $this->view('tipos-cambio.cierre', [
            'title' => 'Tipo de Cambio para Cierre',
            'tipo_cambio' => $tipoCambioCierre,
            'anio' => $anio,
            'mes' => $mes,
            'moneda' => $moneda
        ]);
    }

    /**
     * Eliminar tipo de cambio
     */
    public function delete(Request $request, $id)
    {
        $this->requireAuth();

        try {
            $tipoCambio = $this->tipoCambioModel->find($id);

            if (!$tipoCambio) {
                throw new \Exception('Tipo de cambio no encontrado');
            }

            $this->tipoCambioModel->delete($id);

            $this->setFlash('success', 'Tipo de cambio eliminado exitosamente');
            $this->json(['success' => true]);

        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
