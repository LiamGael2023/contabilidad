<?php

namespace Models;

use Core\Model;

class PlanContable extends Model
{
    protected $table = 'plan_contable';
    protected $fillable = [
        'empresa_id', 'codigo', 'descripcion', 'elemento', 'nivel',
        'codigo_padre', 'naturaleza', 'tipo', 'recibe_saldo',
        'requiere_auxiliar', 'activo'
    ];

    /**
     * Obtener cuentas por empresa
     */
    public function getCuentasByEmpresa($empresaId)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE empresa_id = :empresa_id AND activo = 1
                ORDER BY codigo";
        $stmt = $this->db->query($sql, ['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    /**
     * Buscar cuenta por código
     */
    public function findByCodigo($empresaId, $codigo)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE empresa_id = :empresa_id AND codigo = :codigo
                AND activo = 1 LIMIT 1";
        $stmt = $this->db->query($sql, [
            'empresa_id' => $empresaId,
            'codigo' => $codigo
        ]);
        return $stmt->fetch();
    }

    /**
     * Obtener cuentas por elemento
     */
    public function getCuentasByElemento($empresaId, $elemento)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE empresa_id = :empresa_id AND elemento = :elemento
                AND activo = 1 ORDER BY codigo";
        $stmt = $this->db->query($sql, [
            'empresa_id' => $empresaId,
            'elemento' => $elemento
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener cuentas que reciben saldo (nivel más bajo)
     */
    public function getCuentasRecibeSaldo($empresaId)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE empresa_id = :empresa_id
                AND recibe_saldo = 1
                AND activo = 1
                ORDER BY codigo";
        $stmt = $this->db->query($sql, ['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    /**
     * Cargar Plan Contable General Empresarial
     */
    public function cargarPCGE($empresaId)
    {
        // Datos del PCGE oficial
        $pcge = $this->obtenerPCGEBase();

        $this->db->beginTransaction();
        try {
            foreach ($pcge as $cuenta) {
                $cuenta['empresa_id'] = $empresaId;
                $this->create($cuenta);
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * PCGE Base (principales cuentas)
     */
    private function obtenerPCGEBase()
    {
        return [
            // ELEMENTO 1: ACTIVO DISPONIBLE Y EXIGIBLE
            ['codigo' => '10', 'descripcion' => 'EFECTIVO Y EQUIVALENTES DE EFECTIVO', 'elemento' => 1, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 0],
            ['codigo' => '101', 'descripcion' => 'Caja', 'elemento' => 1, 'nivel' => 3, 'codigo_padre' => '10', 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 0],
            ['codigo' => '1011', 'descripcion' => 'Caja Chica', 'elemento' => 1, 'nivel' => 4, 'codigo_padre' => '101', 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 1],
            ['codigo' => '104', 'descripcion' => 'Cuentas corrientes en instituciones financieras', 'elemento' => 1, 'nivel' => 3, 'codigo_padre' => '10', 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 0],
            ['codigo' => '1041', 'descripcion' => 'Cuentas corrientes operativas', 'elemento' => 1, 'nivel' => 4, 'codigo_padre' => '104', 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 1],

            ['codigo' => '12', 'descripcion' => 'CUENTAS POR COBRAR COMERCIALES - TERCEROS', 'elemento' => 1, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 0],
            ['codigo' => '121', 'descripcion' => 'Facturas, boletas y otros comprobantes por cobrar', 'elemento' => 1, 'nivel' => 3, 'codigo_padre' => '12', 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 0],
            ['codigo' => '1211', 'descripcion' => 'Emitidas', 'elemento' => 1, 'nivel' => 4, 'codigo_padre' => '121', 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 1, 'requiere_auxiliar' => 1],

            // ELEMENTO 2: ACTIVO REALIZABLE
            ['codigo' => '20', 'descripcion' => 'MERCADERÍAS', 'elemento' => 2, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 0],
            ['codigo' => '201', 'descripcion' => 'Mercaderías manufacturadas', 'elemento' => 2, 'nivel' => 3, 'codigo_padre' => '20', 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 1],

            // ELEMENTO 3: ACTIVO INMOVILIZADO
            ['codigo' => '33', 'descripcion' => 'INMUEBLES, MAQUINARIA Y EQUIPO', 'elemento' => 3, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 0],
            ['codigo' => '336', 'descripcion' => 'Equipos diversos', 'elemento' => 3, 'nivel' => 3, 'codigo_padre' => '33', 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 0],
            ['codigo' => '3361', 'descripcion' => 'Equipo para procesamiento de información (de cómputo)', 'elemento' => 3, 'nivel' => 4, 'codigo_padre' => '336', 'naturaleza' => 'DEUDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 1],

            ['codigo' => '39', 'descripcion' => 'DEPRECIACIÓN, AMORTIZACIÓN Y AGOTAMIENTO ACUMULADOS', 'elemento' => 3, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'ACREEDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 0],
            ['codigo' => '391', 'descripcion' => 'Depreciación acumulada', 'elemento' => 3, 'nivel' => 3, 'codigo_padre' => '39', 'naturaleza' => 'ACREEDORA', 'tipo' => 'ACTIVO', 'recibe_saldo' => 1],

            // ELEMENTO 4: PASIVO
            ['codigo' => '40', 'descripcion' => 'TRIBUTOS, CONTRAPRESTACIONES Y APORTES AL SISTEMA DE PENSIONES Y DE SALUD POR PAGAR', 'elemento' => 4, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'ACREEDORA', 'tipo' => 'PASIVO', 'recibe_saldo' => 0],
            ['codigo' => '401', 'descripcion' => 'Gobierno central', 'elemento' => 4, 'nivel' => 3, 'codigo_padre' => '40', 'naturaleza' => 'ACREEDORA', 'tipo' => 'PASIVO', 'recibe_saldo' => 0],
            ['codigo' => '4011', 'descripcion' => 'Impuesto General a las Ventas', 'elemento' => 4, 'nivel' => 4, 'codigo_padre' => '401', 'naturaleza' => 'ACREEDORA', 'tipo' => 'PASIVO', 'recibe_saldo' => 1],
            ['codigo' => '40111', 'descripcion' => 'IGV - Cuenta propia', 'elemento' => 4, 'nivel' => 5, 'codigo_padre' => '4011', 'naturaleza' => 'ACREEDORA', 'tipo' => 'PASIVO', 'recibe_saldo' => 1],
            ['codigo' => '4017', 'descripcion' => 'Impuesto a la Renta', 'elemento' => 4, 'nivel' => 4, 'codigo_padre' => '401', 'naturaleza' => 'ACREEDORA', 'tipo' => 'PASIVO', 'recibe_saldo' => 0],
            ['codigo' => '40171', 'descripcion' => 'Renta de tercera categoría', 'elemento' => 4, 'nivel' => 5, 'codigo_padre' => '4017', 'naturaleza' => 'ACREEDORA', 'tipo' => 'PASIVO', 'recibe_saldo' => 1],

            ['codigo' => '42', 'descripcion' => 'CUENTAS POR PAGAR COMERCIALES - TERCEROS', 'elemento' => 4, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'ACREEDORA', 'tipo' => 'PASIVO', 'recibe_saldo' => 0],
            ['codigo' => '421', 'descripcion' => 'Facturas, boletas y otros comprobantes por pagar', 'elemento' => 4, 'nivel' => 3, 'codigo_padre' => '42', 'naturaleza' => 'ACREEDORA', 'tipo' => 'PASIVO', 'recibe_saldo' => 0],
            ['codigo' => '4211', 'descripcion' => 'Emitidas', 'elemento' => 4, 'nivel' => 4, 'codigo_padre' => '421', 'naturaleza' => 'ACREEDORA', 'tipo' => 'PASIVO', 'recibe_saldo' => 1, 'requiere_auxiliar' => 1],

            // ELEMENTO 5: PATRIMONIO
            ['codigo' => '50', 'descripcion' => 'CAPITAL', 'elemento' => 5, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'ACREEDORA', 'tipo' => 'PATRIMONIO', 'recibe_saldo' => 0],
            ['codigo' => '501', 'descripcion' => 'Capital social', 'elemento' => 5, 'nivel' => 3, 'codigo_padre' => '50', 'naturaleza' => 'ACREEDORA', 'tipo' => 'PATRIMONIO', 'recibe_saldo' => 1],

            ['codigo' => '59', 'descripcion' => 'RESULTADOS ACUMULADOS', 'elemento' => 5, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'ACREEDORA', 'tipo' => 'PATRIMONIO', 'recibe_saldo' => 0],
            ['codigo' => '591', 'descripcion' => 'Utilidades no distribuidas', 'elemento' => 5, 'nivel' => 3, 'codigo_padre' => '59', 'naturaleza' => 'ACREEDORA', 'tipo' => 'PATRIMONIO', 'recibe_saldo' => 1],
            ['codigo' => '592', 'descripcion' => 'Pérdidas acumuladas', 'elemento' => 5, 'nivel' => 3, 'codigo_padre' => '59', 'naturaleza' => 'DEUDORA', 'tipo' => 'PATRIMONIO', 'recibe_saldo' => 1],

            // ELEMENTO 6: GASTOS POR NATURALEZA
            ['codigo' => '60', 'descripcion' => 'COMPRAS', 'elemento' => 6, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'DEUDORA', 'tipo' => 'GASTO', 'recibe_saldo' => 0],
            ['codigo' => '601', 'descripcion' => 'Mercaderías', 'elemento' => 6, 'nivel' => 3, 'codigo_padre' => '60', 'naturaleza' => 'DEUDORA', 'tipo' => 'GASTO', 'recibe_saldo' => 1],

            ['codigo' => '63', 'descripcion' => 'GASTOS DE SERVICIOS PRESTADOS POR TERCEROS', 'elemento' => 6, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'DEUDORA', 'tipo' => 'GASTO', 'recibe_saldo' => 0],
            ['codigo' => '631', 'descripcion' => 'Transporte, correos y gastos de viaje', 'elemento' => 6, 'nivel' => 3, 'codigo_padre' => '63', 'naturaleza' => 'DEUDORA', 'tipo' => 'GASTO', 'recibe_saldo' => 1],
            ['codigo' => '634', 'descripcion' => 'Mantenimiento y reparaciones', 'elemento' => 6, 'nivel' => 3, 'codigo_padre' => '63', 'naturaleza' => 'DEUDORA', 'tipo' => 'GASTO', 'recibe_saldo' => 1],
            ['codigo' => '636', 'descripcion' => 'Servicios básicos', 'elemento' => 6, 'nivel' => 3, 'codigo_padre' => '63', 'naturaleza' => 'DEUDORA', 'tipo' => 'GASTO', 'recibe_saldo' => 1],

            // ELEMENTO 7: INGRESOS
            ['codigo' => '70', 'descripcion' => 'VENTAS', 'elemento' => 7, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'ACREEDORA', 'tipo' => 'INGRESO', 'recibe_saldo' => 0],
            ['codigo' => '701', 'descripcion' => 'Mercaderías', 'elemento' => 7, 'nivel' => 3, 'codigo_padre' => '70', 'naturaleza' => 'ACREEDORA', 'tipo' => 'INGRESO', 'recibe_saldo' => 1],

            // ELEMENTO 8: SALDOS INTERMEDIARIOS DE GESTIÓN
            ['codigo' => '89', 'descripcion' => 'DETERMINACIÓN DEL RESULTADO DEL EJERCICIO', 'elemento' => 8, 'nivel' => 2, 'codigo_padre' => null, 'naturaleza' => 'DEUDORA', 'tipo' => 'RESULTADO', 'recibe_saldo' => 1],
        ];
    }
}
