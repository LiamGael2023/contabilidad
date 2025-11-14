<?php

namespace Models;

use Core\Model;

class DetalleAsiento extends Model
{
    protected $table = 'detalle_asientos';
    protected $fillable = [
        'asiento_id', 'numero_linea', 'cuenta_id', 'codigo_auxiliar',
        'tipo_documento', 'numero_documento', 'glosa',
        'debe', 'haber', 'debe_me', 'haber_me'
    ];

    /**
     * Obtener detalles por asiento
     */
    public function getByAsiento($asientoId)
    {
        $sql = "SELECT d.*, pc.codigo, pc.descripcion as nombre_cuenta
                FROM {$this->table} d
                INNER JOIN plan_contable pc ON d.cuenta_id = pc.id
                WHERE d.asiento_id = :asiento_id
                ORDER BY d.numero_linea";
        $stmt = $this->db->query($sql, ['asiento_id' => $asientoId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener movimientos por cuenta
     */
    public function getByCuenta($cuentaId, $periodoId = null)
    {
        $sql = "SELECT d.*, a.fecha, a.numero_asiento, a.glosa as glosa_asiento
                FROM {$this->table} d
                INNER JOIN asientos_contables a ON d.asiento_id = a.id
                WHERE d.cuenta_id = :cuenta_id
                AND a.estado = 'registrado'";

        $params = ['cuenta_id' => $cuentaId];

        if ($periodoId) {
            $sql .= " AND a.periodo_id = :periodo_id";
            $params['periodo_id'] = $periodoId;
        }

        $sql .= " ORDER BY a.fecha, a.numero_asiento, d.numero_linea";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
}
