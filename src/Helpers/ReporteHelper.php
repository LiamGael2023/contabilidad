<?php

namespace Helpers;

use Models\PlanContable;
use Models\DetalleAsiento;

/**
 * Helper para generar reportes financieros
 */
class ReporteHelper
{
    /**
     * Generar Balance General
     */
    public function generarBalanceGeneral($empresaId, $periodoId)
    {
        $planContableModel = new PlanContable();
        $detalleModel = new DetalleAsiento();

        // Obtener cuentas de balance (elementos 1, 2, 3, 4, 5)
        $activos = $this->obtenerSaldosPorElemento($empresaId, $periodoId, [1, 2, 3]);
        $pasivos = $this->obtenerSaldosPorElemento($empresaId, $periodoId, [4]);
        $patrimonio = $this->obtenerSaldosPorElemento($empresaId, $periodoId, [5]);

        // Calcular totales
        $totalActivo = $this->calcularTotal($activos);
        $totalPasivo = $this->calcularTotal($pasivos);
        $totalPatrimonio = $this->calcularTotal($patrimonio);

        return [
            'activos' => $activos,
            'pasivos' => $pasivos,
            'patrimonio' => $patrimonio,
            'total_activo' => $totalActivo,
            'total_pasivo' => $totalPasivo,
            'total_patrimonio' => $totalPatrimonio,
            'total_pasivo_patrimonio' => $totalPasivo + $totalPatrimonio
        ];
    }

    /**
     * Generar Estado de Resultados
     */
    public function generarEstadoResultados($empresaId, $periodoId)
    {
        // Obtener cuentas de resultados (elementos 6 y 7)
        $ingresos = $this->obtenerSaldosPorElemento($empresaId, $periodoId, [7]);
        $gastos = $this->obtenerSaldosPorElemento($empresaId, $periodoId, [6]);

        // Calcular totales
        $totalIngresos = $this->calcularTotal($ingresos);
        $totalGastos = $this->calcularTotal($gastos);
        $utilidadNeta = $totalIngresos - $totalGastos;

        return [
            'ingresos' => $ingresos,
            'gastos' => $gastos,
            'total_ingresos' => $totalIngresos,
            'total_gastos' => $totalGastos,
            'utilidad_bruta' => $totalIngresos - $totalGastos,
            'utilidad_neta' => $utilidadNeta
        ];
    }

    /**
     * Generar Flujo de Efectivo
     */
    public function generarFlujoEfectivo($empresaId, $periodoId)
    {
        // Obtener movimientos de cuentas de efectivo (elemento 1, cuenta 10)
        $detalleModel = new DetalleAsiento();

        $sql = "SELECT d.*, a.fecha, pc.codigo, pc.descripcion
                FROM detalle_asientos d
                INNER JOIN asientos_contables a ON d.asiento_id = a.id
                INNER JOIN plan_contable pc ON d.cuenta_id = pc.id
                WHERE a.periodo_id = :periodo_id
                AND pc.codigo LIKE '10%'
                AND a.estado = 'registrado'
                ORDER BY a.fecha, a.numero_asiento";

        $movimientos = $detalleModel->query($sql, ['periodo_id' => $periodoId])->fetchAll();

        // Clasificar por actividades
        $operacion = [];
        $inversion = [];
        $financiamiento = [];

        // Calcular totales
        $totalOperacion = 0;
        $totalInversion = 0;
        $totalFinanciamiento = 0;

        foreach ($movimientos as $mov) {
            $monto = $mov['debe'] - $mov['haber'];
            // Aquí se clasificaría según la naturaleza de la operación
            $totalOperacion += $monto;
        }

        return [
            'actividades_operacion' => $operacion,
            'actividades_inversion' => $inversion,
            'actividades_financiamiento' => $financiamiento,
            'total_operacion' => $totalOperacion,
            'total_inversion' => $totalInversion,
            'total_financiamiento' => $totalFinanciamiento,
            'aumento_efectivo' => $totalOperacion + $totalInversion + $totalFinanciamiento
        ];
    }

    /**
     * Obtener saldos por elemento
     */
    private function obtenerSaldosPorElemento($empresaId, $periodoId, $elementos)
    {
        $planContableModel = new PlanContable();
        $detalleModel = new DetalleAsiento();

        $elementosStr = implode(',', $elementos);

        // Obtener cuentas del elemento
        $sql = "SELECT * FROM plan_contable
                WHERE empresa_id = :empresa_id
                AND elemento IN ({$elementosStr})
                AND recibe_saldo = 1
                AND activo = 1
                ORDER BY codigo";

        $cuentas = $planContableModel->query($sql, ['empresa_id' => $empresaId])->fetchAll();

        // Calcular saldo de cada cuenta
        foreach ($cuentas as &$cuenta) {
            $saldo = $this->calcularSaldoCuenta($cuenta['id'], $periodoId);
            $cuenta['saldo'] = $saldo;
        }

        return $cuentas;
    }

    /**
     * Calcular saldo de una cuenta
     */
    private function calcularSaldoCuenta($cuentaId, $periodoId)
    {
        $detalleModel = new DetalleAsiento();
        $planContableModel = new PlanContable();

        $cuenta = $planContableModel->find($cuentaId);

        $sql = "SELECT
                    SUM(d.debe) as total_debe,
                    SUM(d.haber) as total_haber
                FROM detalle_asientos d
                INNER JOIN asientos_contables a ON d.asiento_id = a.id
                WHERE d.cuenta_id = :cuenta_id
                AND a.periodo_id = :periodo_id
                AND a.estado = 'registrado'";

        $result = $detalleModel->query($sql, [
            'cuenta_id' => $cuentaId,
            'periodo_id' => $periodoId
        ])->fetch();

        $debe = $result['total_debe'] ?? 0;
        $haber = $result['total_haber'] ?? 0;

        // Calcular saldo según naturaleza
        if ($cuenta['naturaleza'] === 'DEUDORA') {
            return $debe - $haber;
        } else {
            return $haber - $debe;
        }
    }

    /**
     * Calcular total de un array de cuentas
     */
    private function calcularTotal($cuentas)
    {
        $total = 0;
        foreach ($cuentas as $cuenta) {
            $total += $cuenta['saldo'] ?? 0;
        }
        return $total;
    }
}
