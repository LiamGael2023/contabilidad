<?php

namespace Models;

use Core\Model;

class MovimientoCaja extends Model
{
    protected $table = 'movimientos_caja';
    protected $fillable = [
        'empresa_id', 'banco_id', 'fecha', 'tipo_movimiento', 'tipo_operacion',
        'monto', 'moneda', 'tipo_cambio', 'descripcion', 'numero_operacion',
        'tercero_id', 'categoria', 'asiento_id'
    ];

    /**
     * Obtener movimientos por banco
     */
    public function getByBanco($bancoId, $fechaInicio = null, $fechaFin = null)
    {
        $sql = "SELECT mc.*, b.nombre_banco, b.numero_cuenta,
                       t.razon_social as tercero_nombre
                FROM {$this->table} mc
                INNER JOIN bancos b ON mc.banco_id = b.id
                LEFT JOIN terceros t ON mc.tercero_id = t.id
                WHERE mc.banco_id = :banco_id";

        $params = ['banco_id' => $bancoId];

        if ($fechaInicio && $fechaFin) {
            $sql .= " AND mc.fecha BETWEEN :fecha_inicio AND :fecha_fin";
            $params['fecha_inicio'] = $fechaInicio;
            $params['fecha_fin'] = $fechaFin;
        }

        $sql .= " ORDER BY mc.fecha DESC, mc.id DESC";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener movimientos por empresa
     */
    public function getByEmpresa($empresaId, $fechaInicio = null, $fechaFin = null)
    {
        $sql = "SELECT mc.*, b.nombre_banco, b.numero_cuenta, b.moneda as moneda_banco,
                       t.razon_social as tercero_nombre
                FROM {$this->table} mc
                INNER JOIN bancos b ON mc.banco_id = b.id
                LEFT JOIN terceros t ON mc.tercero_id = t.id
                WHERE mc.empresa_id = :empresa_id";

        $params = ['empresa_id' => $empresaId];

        if ($fechaInicio && $fechaFin) {
            $sql .= " AND mc.fecha BETWEEN :fecha_inicio AND :fecha_fin";
            $params['fecha_inicio'] = $fechaInicio;
            $params['fecha_fin'] = $fechaFin;
        }

        $sql .= " ORDER BY mc.fecha DESC, mc.id DESC";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Registrar ingreso
     */
    public function registrarIngreso($data)
    {
        $data['tipo_movimiento'] = 'ingreso';

        $this->db->beginTransaction();
        try {
            // Crear movimiento
            $movimientoId = $this->create($data);

            // Actualizar saldo del banco
            $bancoModel = new Banco();
            $bancoModel->actualizarSaldo($data['banco_id'], $data['monto'], 'ingreso');

            $this->db->commit();
            return $movimientoId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Registrar egreso
     */
    public function registrarEgreso($data)
    {
        $data['tipo_movimiento'] = 'egreso';

        $this->db->beginTransaction();
        try {
            // Crear movimiento
            $movimientoId = $this->create($data);

            // Actualizar saldo del banco
            $bancoModel = new Banco();
            $bancoModel->actualizarSaldo($data['banco_id'], $data['monto'], 'egreso');

            $this->db->commit();
            return $movimientoId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Obtener flujo de caja del día
     */
    public function getFlujoDia($empresaId, $fecha = null)
    {
        $fecha = $fecha ?? date('Y-m-d');

        $sql = "SELECT
                    SUM(CASE WHEN tipo_movimiento = 'ingreso' THEN monto ELSE 0 END) as total_ingresos,
                    SUM(CASE WHEN tipo_movimiento = 'egreso' THEN monto ELSE 0 END) as total_egresos,
                    SUM(CASE WHEN tipo_movimiento = 'ingreso' THEN monto ELSE -monto END) as saldo_neto
                FROM {$this->table}
                WHERE empresa_id = :empresa_id
                AND fecha = :fecha";

        $stmt = $this->db->query($sql, [
            'empresa_id' => $empresaId,
            'fecha' => $fecha
        ]);
        return $stmt->fetch();
    }

    /**
     * Obtener flujo de caja del mes
     */
    public function getFlujoMes($empresaId, $anio, $mes)
    {
        $sql = "SELECT
                    DAY(fecha) as dia,
                    SUM(CASE WHEN tipo_movimiento = 'ingreso' THEN monto ELSE 0 END) as ingresos,
                    SUM(CASE WHEN tipo_movimiento = 'egreso' THEN monto ELSE 0 END) as egresos
                FROM {$this->table}
                WHERE empresa_id = :empresa_id
                AND YEAR(fecha) = :anio
                AND MONTH(fecha) = :mes
                GROUP BY DAY(fecha)
                ORDER BY dia";

        $stmt = $this->db->query($sql, [
            'empresa_id' => $empresaId,
            'anio' => $anio,
            'mes' => $mes
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Alias para compatibilidad
     */
    public function getFlujoDiario($empresaId, $fecha = null)
    {
        return $this->getFlujoDia($empresaId, $fecha);
    }

    /**
     * Alias para compatibilidad
     */
    public function getFlujoMensual($empresaId, $anio, $mes)
    {
        return $this->getFlujoMes($empresaId, $anio, $mes);
    }
}
