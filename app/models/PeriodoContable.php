<?php

namespace Models;

use Core\Model;

class PeriodoContable extends Model
{
    protected $table = 'periodos_contables';
    protected $fillable = [
        'empresa_id', 'anio', 'mes', 'fecha_inicio', 'fecha_fin', 'estado'
    ];

    /**
     * Obtener períodos por empresa
     */
    public function getByEmpresa($empresaId)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE empresa_id = :empresa_id
                ORDER BY anio DESC, mes DESC";
        $stmt = $this->db->query($sql, ['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener período actual (último abierto)
     */
    public function getPeriodoActual($empresaId)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE empresa_id = :empresa_id
                AND estado = 'abierto'
                ORDER BY anio DESC, mes DESC
                LIMIT 1";
        $stmt = $this->db->query($sql, ['empresa_id' => $empresaId]);
        return $stmt->fetch();
    }

    /**
     * Crear período
     */
    public function crearPeriodo($empresaId, $anio, $mes)
    {
        // Calcular fechas
        $fechaInicio = date('Y-m-01', strtotime("{$anio}-{$mes}-01"));
        $fechaFin = date('Y-m-t', strtotime("{$anio}-{$mes}-01"));

        return $this->create([
            'empresa_id' => $empresaId,
            'anio' => $anio,
            'mes' => $mes,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => 'abierto'
        ]);
    }

    /**
     * Cerrar período
     */
    public function cerrar($id, $usuarioId)
    {
        return $this->update($id, [
            'estado' => 'cerrado',
            'cerrado_por' => $usuarioId,
            'fecha_cierre' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Reabrir período
     */
    public function reabrir($id)
    {
        return $this->update($id, [
            'estado' => 'abierto',
            'cerrado_por' => null,
            'fecha_cierre' => null
        ]);
    }

    /**
     * Verificar si existe período
     */
    public function existePeriodo($empresaId, $anio, $mes)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}
                WHERE empresa_id = :empresa_id
                AND anio = :anio
                AND mes = :mes";
        $stmt = $this->db->query($sql, [
            'empresa_id' => $empresaId,
            'anio' => $anio,
            'mes' => $mes
        ]);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
}
