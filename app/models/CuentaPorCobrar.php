<?php

namespace Models;

use Core\Model;

class CuentaPorCobrar extends Model
{
    protected $table = 'cuentas_por_cobrar';
    protected $fillable = [
        'empresa_id', 'periodo_id', 'tercero_id', 'registro_venta_id',
        'tipo_documento', 'serie', 'numero', 'fecha_emision', 'fecha_vencimiento',
        'moneda', 'tipo_cambio', 'importe_total', 'saldo_pendiente',
        'estado', 'observaciones'
    ];

    /**
     * Obtener cuentas por cobrar por empresa
     */
    public function getByEmpresa($empresaId, $estado = null)
    {
        $sql = "SELECT cxc.*, t.razon_social as cliente_nombre, t.numero_documento
                FROM {$this->table} cxc
                INNER JOIN terceros t ON cxc.tercero_id = t.id
                WHERE cxc.empresa_id = :empresa_id";

        $params = ['empresa_id' => $empresaId];

        if ($estado) {
            $sql .= " AND cxc.estado = :estado";
            $params['estado'] = $estado;
        }

        $sql .= " ORDER BY cxc.fecha_vencimiento ASC";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener cuentas por estado
     */
    public function getByEstado($empresaId, $estado)
    {
        return $this->getByEmpresa($empresaId, $estado);
    }

    /**
     * Obtener total por estado
     */
    public function getTotalPorEstado($empresaId, $estado)
    {
        $sql = "SELECT COALESCE(SUM(saldo_pendiente), 0) as total
                FROM {$this->table}
                WHERE empresa_id = :empresa_id
                AND estado = :estado";

        $stmt = $this->db->query($sql, [
            'empresa_id' => $empresaId,
            'estado' => $estado
        ]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Obtener cuentas vencidas
     */
    public function getVencidas($empresaId)
    {
        $sql = "SELECT cxc.*, t.razon_social as cliente_nombre, t.numero_documento,
                       DATEDIFF(CURDATE(), cxc.fecha_vencimiento) as dias_vencidos
                FROM {$this->table} cxc
                INNER JOIN terceros t ON cxc.tercero_id = t.id
                WHERE cxc.empresa_id = :empresa_id
                AND cxc.estado = 'pendiente'
                AND cxc.fecha_vencimiento < CURDATE()
                ORDER BY dias_vencidos DESC";

        $stmt = $this->db->query($sql, ['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener por vencer (próximos 30 días)
     */
    public function getPorVencer($empresaId, $dias = 30)
    {
        $sql = "SELECT cxc.*, t.razon_social as cliente_nombre, t.numero_documento,
                       DATEDIFF(cxc.fecha_vencimiento, CURDATE()) as dias_restantes
                FROM {$this->table} cxc
                INNER JOIN terceros t ON cxc.tercero_id = t.id
                WHERE cxc.empresa_id = :empresa_id
                AND cxc.estado = 'pendiente'
                AND cxc.fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :dias DAY)
                ORDER BY cxc.fecha_vencimiento ASC";

        $stmt = $this->db->query($sql, [
            'empresa_id' => $empresaId,
            'dias' => $dias
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Registrar pago
     */
    public function registrarPago($id, $montoPago, $fechaPago, $metodoPago, $observaciones = null)
    {
        $cxc = $this->find($id);
        if (!$cxc) {
            throw new \Exception('Cuenta por cobrar no encontrada');
        }

        $nuevoSaldo = $cxc['saldo_pendiente'] - $montoPago;

        if ($nuevoSaldo < 0) {
            throw new \Exception('El monto del pago excede el saldo pendiente');
        }

        // Actualizar saldo
        $this->update($id, [
            'saldo_pendiente' => $nuevoSaldo,
            'estado' => $nuevoSaldo == 0 ? 'pagado' : 'parcial'
        ]);

        // Registrar el pago en tabla de pagos
        $pagoModel = new Pago();
        $pagoModel->create([
            'empresa_id' => $cxc['empresa_id'],
            'cuenta_por_cobrar_id' => $id,
            'fecha_pago' => $fechaPago,
            'monto' => $montoPago,
            'metodo_pago' => $metodoPago,
            'observaciones' => $observaciones
        ]);

        return $nuevoSaldo;
    }

    /**
     * Obtener antigüedad de saldos
     */
    public function getAntiguedadSaldos($empresaId)
    {
        $sql = "SELECT
                    t.razon_social as cliente,
                    SUM(CASE WHEN DATEDIFF(CURDATE(), cxc.fecha_vencimiento) <= 0 THEN cxc.saldo_pendiente ELSE 0 END) as vigente,
                    SUM(CASE WHEN DATEDIFF(CURDATE(), cxc.fecha_vencimiento) BETWEEN 1 AND 30 THEN cxc.saldo_pendiente ELSE 0 END) as vencido_30,
                    SUM(CASE WHEN DATEDIFF(CURDATE(), cxc.fecha_vencimiento) BETWEEN 31 AND 60 THEN cxc.saldo_pendiente ELSE 0 END) as vencido_60,
                    SUM(CASE WHEN DATEDIFF(CURDATE(), cxc.fecha_vencimiento) BETWEEN 61 AND 90 THEN cxc.saldo_pendiente ELSE 0 END) as vencido_90,
                    SUM(CASE WHEN DATEDIFF(CURDATE(), cxc.fecha_vencimiento) > 90 THEN cxc.saldo_pendiente ELSE 0 END) as vencido_mas_90,
                    SUM(cxc.saldo_pendiente) as total
                FROM {$this->table} cxc
                INNER JOIN terceros t ON cxc.tercero_id = t.id
                WHERE cxc.empresa_id = :empresa_id
                AND cxc.estado IN ('pendiente', 'parcial')
                GROUP BY t.id, t.razon_social
                HAVING total > 0
                ORDER BY total DESC";

        $stmt = $this->db->query($sql, ['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener totales por estado
     */
    public function getTotalesPorEstado($empresaId)
    {
        $sql = "SELECT
                    estado,
                    COUNT(*) as cantidad,
                    SUM(importe_total) as total_facturado,
                    SUM(saldo_pendiente) as total_pendiente
                FROM {$this->table}
                WHERE empresa_id = :empresa_id
                GROUP BY estado";

        $stmt = $this->db->query($sql, ['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }
}
