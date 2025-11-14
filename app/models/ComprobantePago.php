<?php

namespace Models;

use Core\Model;

class ComprobantePago extends Model
{
    protected $table = 'comprobantes_pago';
    protected $fillable = [
        'empresa_id', 'periodo_id', 'tipo_comprobante_id', 'serie', 'numero',
        'fecha_emision', 'fecha_vencimiento', 'tercero_id', 'moneda', 'tipo_cambio',
        'base_imponible', 'igv', 'total', 'detraccion', 'retencion', 'percepcion',
        'estado', 'tipo_operacion', 'observaciones'
    ];

    /**
     * Obtener comprobantes por período
     */
    public function getByPeriodo($periodoId, $tipoOperacion = null)
    {
        $sql = "SELECT c.*, tc.descripcion as tipo_comprobante, t.razon_social as tercero
                FROM {$this->table} c
                INNER JOIN tipos_comprobante tc ON c.tipo_comprobante_id = tc.id
                INNER JOIN terceros t ON c.tercero_id = t.id
                WHERE c.periodo_id = :periodo_id";

        $params = ['periodo_id' => $periodoId];

        if ($tipoOperacion) {
            $sql .= " AND c.tipo_operacion = :tipo_operacion";
            $params['tipo_operacion'] = $tipoOperacion;
        }

        $sql .= " ORDER BY c.fecha_emision DESC, c.serie, c.numero";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener ventas
     */
    public function getVentas($periodoId)
    {
        return $this->getByPeriodo($periodoId, 'venta');
    }

    /**
     * Obtener compras
     */
    public function getCompras($periodoId)
    {
        return $this->getByPeriodo($periodoId, 'compra');
    }

    /**
     * Crear comprobante y asiento contable
     */
    public function crearConAsiento($comprobanteData, $asientoData, $detallesAsiento)
    {
        $this->db->beginTransaction();
        try {
            // Crear asiento contable
            $asientoModel = new AsientoContable();
            $asientoId = $asientoModel->crearConDetalles($asientoData, $detallesAsiento);

            // Crear comprobante
            $comprobanteData['asiento_id'] = $asientoId;
            $comprobanteId = $this->create($comprobanteData);

            $this->db->commit();
            return $comprobanteId;
        } catch (\Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * Anular comprobante
     */
    public function anular($id, $usuarioId, $motivo)
    {
        $this->db->beginTransaction();
        try {
            // Obtener comprobante
            $comprobante = $this->find($id);
            if (!$comprobante) {
                throw new \Exception("Comprobante no encontrado");
            }

            // Anular comprobante
            $this->update($id, ['estado' => 'anulado']);

            // Anular asiento asociado
            if ($comprobante['asiento_id']) {
                $asientoModel = new AsientoContable();
                $asientoModel->anular($comprobante['asiento_id'], $usuarioId, $motivo);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
