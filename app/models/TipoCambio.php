<?php

namespace Models;

use Core\Model;

class TipoCambio extends Model
{
    protected $table = 'tipos_cambio';
    protected $fillable = [
        'fecha', 'moneda', 'compra', 'venta', 'fuente'
    ];

    /**
     * Obtener tipo de cambio por fecha
     */
    public function getByFecha($fecha, $moneda = 'USD')
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE fecha = :fecha
                AND moneda = :moneda
                LIMIT 1";

        $stmt = $this->db->query($sql, [
            'fecha' => $fecha,
            'moneda' => $moneda
        ]);
        return $stmt->fetch();
    }

    /**
     * Obtener tipo de cambio actual (último registrado)
     */
    public function getActual($moneda = 'USD')
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE moneda = :moneda
                ORDER BY fecha DESC
                LIMIT 1";

        $stmt = $this->db->query($sql, ['moneda' => $moneda]);
        return $stmt->fetch();
    }

    /**
     * Obtener tipos de cambio por rango de fechas
     */
    public function getByRango($fechaInicio, $fechaFin, $moneda = 'USD')
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE fecha BETWEEN :fecha_inicio AND :fecha_fin
                AND moneda = :moneda
                ORDER BY fecha DESC";

        $stmt = $this->db->query($sql, [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'moneda' => $moneda
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Importar tipo de cambio desde SUNAT
     * (Esta función necesitaría integración con API de SUNAT)
     */
    public function importarDesdeSUNAT($fecha)
    {
        // TODO: Implementar integración con API de SUNAT
        // Por ahora, retorna false indicando que debe ingresarse manualmente
        return false;
    }

    /**
     * Registrar tipo de cambio
     */
    public function registrar($fecha, $compra, $venta, $moneda = 'USD', $fuente = 'Manual')
    {
        // Verificar si ya existe
        $existe = $this->getByFecha($fecha, $moneda);

        if ($existe) {
            // Actualizar
            $this->update($existe['id'], [
                'compra' => $compra,
                'venta' => $venta,
                'fuente' => $fuente
            ]);
            return $existe['id'];
        } else {
            // Crear nuevo
            return $this->create([
                'fecha' => $fecha,
                'moneda' => $moneda,
                'compra' => $compra,
                'venta' => $venta,
                'fuente' => $fuente
            ]);
        }
    }

    /**
     * Obtener tipo de cambio promedio del mes
     */
    public function getPromedioMes($anio, $mes, $moneda = 'USD')
    {
        $sql = "SELECT
                    AVG(compra) as compra_promedio,
                    AVG(venta) as venta_promedio,
                    MIN(compra) as compra_min,
                    MAX(compra) as compra_max,
                    MIN(venta) as venta_min,
                    MAX(venta) as venta_max
                FROM {$this->table}
                WHERE YEAR(fecha) = :anio
                AND MONTH(fecha) = :mes
                AND moneda = :moneda";

        $stmt = $this->db->query($sql, [
            'anio' => $anio,
            'mes' => $mes,
            'moneda' => $moneda
        ]);
        return $stmt->fetch();
    }

    /**
     * Obtener tipo de cambio para cierre del mes (último día del mes)
     */
    public function getTipoCambioCierre($anio, $mes, $moneda = 'USD')
    {
        $ultimoDia = date('Y-m-t', strtotime("$anio-$mes-01"));

        $sql = "SELECT * FROM {$this->table}
                WHERE fecha <= :fecha
                AND moneda = :moneda
                ORDER BY fecha DESC
                LIMIT 1";

        $stmt = $this->db->query($sql, [
            'fecha' => $ultimoDia,
            'moneda' => $moneda
        ]);
        return $stmt->fetch();
    }
}
