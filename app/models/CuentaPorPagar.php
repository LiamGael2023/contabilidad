<?php

namespace Models;

use Core\Model;

class CuentaPorPagar extends Model
{
    protected $table = 'cuentas_por_pagar';
    protected $fillable = [
        'empresa_id', 'periodo_id', 'tercero_id', 'registro_compra_id',
        'tipo_documento', 'serie', 'numero', 'fecha_emision', 'fecha_vencimiento',
        'moneda', 'tipo_cambio', 'importe_total', 'saldo_pendiente',
        'estado', 'observaciones'
    ];

    /**
     * Obtener cuentas por pagar por empresa
     */
    public function getByEmpresa($empresaId, $estado = null)
    {
        $sql = "SELECT cxp.*, t.razon_social as proveedor_nombre, t.numero_documento
                FROM {$this->table} cxp
                INNER JOIN terceros t ON cxp.tercero_id = t.id
                WHERE cxp.empresa_id = :empresa_id";

        $params = ['empresa_id' => $empresaId];

        if ($estado) {
            $sql .= " AND cxp.estado = :estado";
            $params['estado'] = $estado;
        }

        $sql .= " ORDER BY cxp.fecha_vencimiento ASC";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener cuentas vencidas
     */
    public function getVencidas($empresaId)
    {
        $sql = "SELECT cxp.*, t.razon_social as proveedor_nombre, t.numero_documento,
                       DATEDIFF(CURDATE(), cxp.fecha_vencimiento) as dias_vencidos
                FROM {$this->table} cxp
                INNER JOIN terceros t ON cxp.tercero_id = t.id
                WHERE cxp.empresa_id = :empresa_id
                AND cxp.estado = 'pendiente'
                AND cxp.fecha_vencimiento < CURDATE()
                ORDER BY dias_vencidos DESC";

        $stmt = $this->db->query($sql, ['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener por pagar (próximos 30 días)
     */
    public function getPorPagar($empresaId, $dias = 30)
    {
        $sql = "SELECT cxp.*, t.razon_social as proveedor_nombre, t.numero_documento,
                       DATEDIFF(cxp.fecha_vencimiento, CURDATE()) as dias_restantes
                FROM {$this->table} cxp
                INNER JOIN terceros t ON cxp.tercero_id = t.id
                WHERE cxp.empresa_id = :empresa_id
                AND cxp.estado = 'pendiente'
                AND cxp.fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :dias DAY)
                ORDER BY cxp.fecha_vencimiento ASC";

        $stmt = $this->db->query($sql, [
            'empresa_id' => $empresaId,
            'dias' => $dias
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Registrar pago a proveedor
     */
    public function registrarPago($id, $montoPago, $fechaPago, $metodoPago, $observaciones = null)
    {
        $cxp = $this->find($id);
        if (!$cxp) {
            throw new \Exception('Cuenta por pagar no encontrada');
        }

        $nuevoSaldo = $cxp['saldo_pendiente'] - $montoPago;

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
            'empresa_id' => $cxp['empresa_id'],
            'cuenta_por_pagar_id' => $id,
            'fecha_pago' => $fechaPago,
            'monto' => $montoPago,
            'metodo_pago' => $metodoPago,
            'observaciones' => $observaciones
        ]);

        return $nuevoSaldo;
    }

    /**
     * Obtener antigüedad de saldos por pagar
     */
    public function getAntiguedadSaldos($empresaId)
    {
        $sql = "SELECT
                    t.razon_social as proveedor,
                    SUM(CASE WHEN DATEDIFF(CURDATE(), cxp.fecha_vencimiento) <= 0 THEN cxp.saldo_pendiente ELSE 0 END) as vigente,
                    SUM(CASE WHEN DATEDIFF(CURDATE(), cxp.fecha_vencimiento) BETWEEN 1 AND 30 THEN cxp.saldo_pendiente ELSE 0 END) as vencido_30,
                    SUM(CASE WHEN DATEDIFF(CURDATE(), cxp.fecha_vencimiento) BETWEEN 31 AND 60 THEN cxp.saldo_pendiente ELSE 0 END) as vencido_60,
                    SUM(CASE WHEN DATEDIFF(CURDATE(), cxp.fecha_vencimiento) BETWEEN 61 AND 90 THEN cxp.saldo_pendiente ELSE 0 END) as vencido_90,
                    SUM(CASE WHEN DATEDIFF(CURDATE(), cxp.fecha_vencimiento) > 90 THEN cxp.saldo_pendiente ELSE 0 END) as vencido_mas_90,
                    SUM(cxp.saldo_pendiente) as total
                FROM {$this->table} cxp
                INNER JOIN terceros t ON cxp.tercero_id = t.id
                WHERE cxp.empresa_id = :empresa_id
                AND cxp.estado IN ('pendiente', 'parcial')
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
