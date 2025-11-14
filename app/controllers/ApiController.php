<?php

namespace Controllers;

use Core\Controller;
use Core\Request;
use Models\PlanContable;

class ApiController extends Controller
{
    /**
     * Buscar cuentas contables
     */
    public function buscarCuentas(Request $request)
    {
        if (!isset($_SESSION['empresa_id'])) {
            $this->json(['error' => 'No hay empresa seleccionada'], 400);
        }

        $empresaId = $_SESSION['empresa_id'];
        $termino = $request->get('q', '');

        $planContableModel = new PlanContable();

        $sql = "SELECT id, codigo, descripcion
                FROM plan_contable
                WHERE empresa_id = :empresa_id
                AND activo = 1
                AND recibe_saldo = 1
                AND (codigo LIKE :termino OR descripcion LIKE :termino)
                ORDER BY codigo
                LIMIT 20";

        $cuentas = $planContableModel->query($sql, [
            'empresa_id' => $empresaId,
            'termino' => "%{$termino}%"
        ])->fetchAll();

        $this->json(['cuentas' => $cuentas]);
    }

    /**
     * Obtener tipos de comprobante
     */
    public function tiposComprobante(Request $request)
    {
        $sql = "SELECT id, codigo, descripcion FROM tipos_comprobante WHERE activo = 1 ORDER BY codigo";

        $db = \Core\Database::getInstance();
        $tipos = $db->query($sql)->fetchAll();

        $this->json(['tipos' => $tipos]);
    }
}
