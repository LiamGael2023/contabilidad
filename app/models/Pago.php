<?php

namespace Models;

use Core\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $fillable = [
        'empresa_id', 'cuenta_por_cobrar_id', 'cuenta_por_pagar_id',
        'fecha_pago', 'monto', 'metodo_pago', 'numero_operacion',
        'banco_id', 'observaciones'
    ];

    /**
     * Obtener pagos recibidos (de clientes)
     */
    public function getPagosRecibidos($empresaId, $fechaInicio = null, $fechaFin = null)
    {
        $sql = "SELECT p.*, cxc.tipo_documento, cxc.serie, cxc.numero,
                       t.razon_social as cliente_nombre
                FROM {$this->table} p
                INNER JOIN cuentas_por_cobrar cxc ON p.cuenta_por_cobrar_id = cxc.id
                INNER JOIN terceros t ON cxc.tercero_id = t.id
                WHERE p.empresa_id = :empresa_id
                AND p.cuenta_por_cobrar_id IS NOT NULL";

        $params = ['empresa_id' => $empresaId];

        if ($fechaInicio && $fechaFin) {
            $sql .= " AND p.fecha_pago BETWEEN :fecha_inicio AND :fecha_fin";
            $params['fecha_inicio'] = $fechaInicio;
            $params['fecha_fin'] = $fechaFin;
        }

        $sql .= " ORDER BY p.fecha_pago DESC";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener pagos efectuados (a proveedores)
     */
    public function getPagosEfectuados($empresaId, $fechaInicio = null, $fechaFin = null)
    {
        $sql = "SELECT p.*, cxp.tipo_documento, cxp.serie, cxp.numero,
                       t.razon_social as proveedor_nombre
                FROM {$this->table} p
                INNER JOIN cuentas_por_pagar cxp ON p.cuenta_por_pagar_id = cxp.id
                INNER JOIN terceros t ON cxp.tercero_id = t.id
                WHERE p.empresa_id = :empresa_id
                AND p.cuenta_por_pagar_id IS NOT NULL";

        $params = ['empresa_id' => $empresaId];

        if ($fechaInicio && $fechaFin) {
            $sql .= " AND p.fecha_pago BETWEEN :fecha_inicio AND :fecha_fin";
            $params['fecha_inicio'] = $fechaInicio;
            $params['fecha_fin'] = $fechaFin;
        }

        $sql .= " ORDER BY p.fecha_pago DESC";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener total cobrado en un período
     */
    public function getTotalCobrado($empresaId, $fechaInicio, $fechaFin)
    {
        $sql = "SELECT COALESCE(SUM(monto), 0) as total
                FROM {$this->table}
                WHERE empresa_id = :empresa_id
                AND cuenta_por_cobrar_id IS NOT NULL
                AND fecha_pago BETWEEN :fecha_inicio AND :fecha_fin";

        $stmt = $this->db->query($sql, [
            'empresa_id' => $empresaId,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Obtener total pagado en un período
     */
    public function getTotalPagado($empresaId, $fechaInicio, $fechaFin)
    {
        $sql = "SELECT COALESCE(SUM(monto), 0) as total
                FROM {$this->table}
                WHERE empresa_id = :empresa_id
                AND cuenta_por_pagar_id IS NOT NULL
                AND fecha_pago BETWEEN :fecha_inicio AND :fecha_fin";

        $stmt = $this->db->query($sql, [
            'empresa_id' => $empresaId,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}
