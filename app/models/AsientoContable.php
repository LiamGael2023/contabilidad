<?php

namespace Models;

use Core\Model;

class AsientoContable extends Model
{
    protected $table = 'asientos_contables';
    protected $fillable = [
        'empresa_id', 'periodo_id', 'numero_asiento', 'fecha',
        'tipo_comprobante_id', 'serie_comprobante', 'numero_comprobante',
        'glosa', 'tipo_cambio', 'estado', 'usuario_id'
    ];

    /**
     * Obtener asientos por período
     */
    public function getByPeriodo($periodoId)
    {
        $sql = "SELECT a.*, tc.descripcion as tipo_comprobante, u.nombre as usuario
                FROM {$this->table} a
                LEFT JOIN tipos_comprobante tc ON a.tipo_comprobante_id = tc.id
                LEFT JOIN usuarios u ON a.usuario_id = u.id
                WHERE a.periodo_id = :periodo_id
                AND a.estado != 'anulado'
                ORDER BY a.numero_asiento";
        $stmt = $this->db->query($sql, ['periodo_id' => $periodoId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener siguiente número de asiento
     */
    public function getSiguienteNumero($periodoId)
    {
        $sql = "SELECT MAX(numero_asiento) as max_numero
                FROM {$this->table}
                WHERE periodo_id = :periodo_id";
        $stmt = $this->db->query($sql, ['periodo_id' => $periodoId]);
        $result = $stmt->fetch();
        return ($result['max_numero'] ?? 0) + 1;
    }

    /**
     * Crear asiento con detalles
     */
    public function crearConDetalles($asientoData, $detalles)
    {
        $this->db->beginTransaction();
        try {
            // Validar que esté balanceado
            $totalDebe = array_sum(array_column($detalles, 'debe'));
            $totalHaber = array_sum(array_column($detalles, 'haber'));

            if (round($totalDebe, 2) != round($totalHaber, 2)) {
                throw new \Exception("El asiento no está balanceado. Debe: {$totalDebe}, Haber: {$totalHaber}");
            }

            // Crear asiento
            $asientoData['total_debe'] = $totalDebe;
            $asientoData['total_haber'] = $totalHaber;
            $asientoId = $this->create($asientoData);

            // Crear detalles
            $detalleModel = new DetalleAsiento();
            foreach ($detalles as $index => $detalle) {
                $detalle['asiento_id'] = $asientoId;
                $detalle['numero_linea'] = $index + 1;
                $detalleModel->create($detalle);
            }

            $this->db->commit();
            return $asientoId;
        } catch (\Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * Anular asiento
     */
    public function anular($id, $usuarioId, $motivo)
    {
        $data = [
            'estado' => 'anulado',
            'anulado_por' => $usuarioId,
            'fecha_anulacion' => date('Y-m-d H:i:s'),
            'motivo_anulacion' => $motivo
        ];
        return $this->update($id, $data);
    }

    /**
     * Obtener asiento con detalles
     */
    public function getConDetalles($id)
    {
        $asiento = $this->find($id);
        if ($asiento) {
            $detalleModel = new DetalleAsiento();
            $asiento['detalles'] = $detalleModel->getByAsiento($id);
        }
        return $asiento;
    }
}
