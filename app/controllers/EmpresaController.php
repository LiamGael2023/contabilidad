<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\Empresa;
use Models\PeriodoContable;
use Models\PlanContable;

class EmpresaController extends Controller
{
    private $empresaModel;
    private $periodoModel;
    private $planContableModel;

    public function __construct()
    {
        parent::__construct();
        $this->empresaModel = new Empresa();
        $this->periodoModel = new PeriodoContable();
        $this->planContableModel = new PlanContable();
    }

    /**
     * Listar empresas
     */
    public function index(Request $request)
    {
        $this->requireAuth();

        $empresas = $this->empresaModel->all();

        $this->view('empresas.index', [
            'title' => 'Empresas',
            'empresas' => $empresas
        ]);
    }

    /**
     * Mostrar formulario de nueva empresa
     */
    public function create(Request $request)
    {
        $this->requireAuth();

        $this->view('empresas.create', [
            'title' => 'Nueva Empresa',
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Guardar nueva empresa
     */
    public function store(Request $request)
    {
        $this->requireAuth();

        if (!$request->isPost()) {
            $this->redirect('empresas');
        }

        try {
            // Validar RUC
            $ruc = $request->post('ruc');
            if (!$this->empresaModel->validarRuc($ruc)) {
                throw new \Exception('RUC inválido. Debe tener 11 dígitos.');
            }

            // Verificar si ya existe
            if ($this->empresaModel->findByRuc($ruc)) {
                throw new \Exception('Ya existe una empresa con ese RUC.');
            }

            // Crear empresa
            $data = [
                'ruc' => $ruc,
                'razon_social' => $request->post('razon_social'),
                'nombre_comercial' => $request->post('nombre_comercial'),
                'direccion' => $request->post('direccion'),
                'departamento' => $request->post('departamento'),
                'provincia' => $request->post('provincia'),
                'distrito' => $request->post('distrito'),
                'telefono' => $request->post('telefono'),
                'email' => $request->post('email'),
                'actividad_economica' => $request->post('actividad_economica'),
                'regimen_tributario' => $request->post('regimen_tributario'),
                'tipo_contribuyente' => $request->post('tipo_contribuyente'),
                'fecha_inicio_actividades' => $request->post('fecha_inicio_actividades'),
                'representante_legal' => $request->post('representante_legal'),
                'dni_representante' => $request->post('dni_representante'),
                'activo' => 1
            ];

            $empresaId = $this->empresaModel->create($data);

            // Cargar Plan Contable General Empresarial
            $this->planContableModel->cargarPCGE($empresaId);

            // Crear período contable inicial
            $fechaInicio = $request->post('fecha_inicio_actividades');
            if ($fechaInicio) {
                $anio = date('Y', strtotime($fechaInicio));
                $mes = date('n', strtotime($fechaInicio));
                $this->periodoModel->crearPeriodo($empresaId, $anio, $mes);
            }

            $this->setFlash('success', 'Empresa creada exitosamente');
            $this->redirect('empresas');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('empresas/nueva');
        }
    }

    /**
     * Editar empresa
     */
    public function edit(Request $request, $id)
    {
        $this->requireAuth();

        $empresa = $this->empresaModel->find($id);
        if (!$empresa) {
            $this->setFlash('error', 'Empresa no encontrada');
            $this->redirect('empresas');
        }

        $this->view('empresas.edit', [
            'title' => 'Editar Empresa',
            'empresa' => $empresa,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Actualizar empresa
     */
    public function update(Request $request, $id)
    {
        $this->requireAuth();

        if (!$request->isPost()) {
            $this->redirect('empresas');
        }

        try {
            $data = [
                'razon_social' => $request->post('razon_social'),
                'nombre_comercial' => $request->post('nombre_comercial'),
                'direccion' => $request->post('direccion'),
                'departamento' => $request->post('departamento'),
                'provincia' => $request->post('provincia'),
                'distrito' => $request->post('distrito'),
                'telefono' => $request->post('telefono'),
                'email' => $request->post('email'),
                'actividad_economica' => $request->post('actividad_economica'),
                'regimen_tributario' => $request->post('regimen_tributario'),
                'representante_legal' => $request->post('representante_legal'),
                'dni_representante' => $request->post('dni_representante')
            ];

            $this->empresaModel->update($id, $data);

            $this->setFlash('success', 'Empresa actualizada exitosamente');
            $this->redirect('empresas');

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect("empresas/editar/{$id}");
        }
    }

    /**
     * Eliminar empresa
     */
    public function delete(Request $request, $id)
    {
        $this->requireAuth();

        if (!$request->isPost()) {
            $this->redirect('empresas');
        }

        try {
            $this->empresaModel->delete($id);
            $this->setFlash('success', 'Empresa eliminada exitosamente');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Error al eliminar empresa: ' . $e->getMessage());
        }

        $this->redirect('empresas');
    }
}
