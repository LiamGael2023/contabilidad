<?php

namespace Models;

use Core\Model;

class RegistroVenta extends Model
{
    protected $table = 'registro_ventas';
    protected $fillable = [
        'empresa_id', 'periodo_id', 'correlativo', 'fecha_emision',
        'fecha_vencimiento', 'tipo_comprobante', 'serie', 'numero',
        'tipo_documento_cliente', 'numero_documento_cliente', 'razon_social_cliente',
        'valor_exportacion', 'base_imponible', 'igv', 'exonerado', 'inafecto',
        'isc', 'otros_tributos', 'importe_total', 'tipo_cambio', 'moneda',
        'fecha_emision_modificado', 'tipo_comprobante_modificado',
        'serie_modificado', 'numero_modificado', 'estado'
    ];

    /**
     * Obtener ventas por período
     */
    public function getByPeriodo($periodoId)
    {
        $sql = "SELECT rv.*, t.razon_social as cliente_nombre
                FROM {$this->table} rv
                LEFT JOIN terceros t ON rv.numero_documento_cliente = t.numero_documento
                WHERE rv.periodo_id = :periodo_id
                ORDER BY rv.fecha_emision DESC, rv.correlativo DESC";

        $stmt = $this->db->query($sql, ['periodo_id' => $periodoId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener ventas por rango de fechas
     */
    public function getByFechas($empresaId, $fechaInicio, $fechaFin)
    {
        $sql = "SELECT rv.*, t.razon_social as cliente_nombre
                FROM {$this->table} rv
                LEFT JOIN terceros t ON rv.numero_documento_cliente = t.numero_documento
                WHERE rv.empresa_id = :empresa_id
                AND rv.fecha_emision BETWEEN :fecha_inicio AND :fecha_fin
                ORDER BY rv.fecha_emision, rv.correlativo";

        $stmt = $this->db->query($sql, [
            'empresa_id' => $empresaId,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener siguiente correlativo
     */
    public function getSiguienteCorrelativo($periodoId)
    {
        $sql = "SELECT COALESCE(MAX(correlativo), 0) + 1 as siguiente
                FROM {$this->table}
                WHERE periodo_id = :periodo_id";

        $stmt = $this->db->query($sql, ['periodo_id' => $periodoId]);
        $result = $stmt->fetch();
        return $result['siguiente'] ?? 1;
    }

    /**
     * Calcular totales por período
     */
    public function getTotalesPeriodo($periodoId)
    {
        $sql = "SELECT
                    COUNT(*) as total_comprobantes,
                    SUM(base_imponible) as total_base,
                    SUM(igv) as total_igv,
                    SUM(exonerado) as total_exonerado,
                    SUM(inafecto) as total_inafecto,
                    SUM(importe_total) as total_general
                FROM {$this->table}
                WHERE periodo_id = :periodo_id
                AND estado = 'registrado'";

        $stmt = $this->db->query($sql, ['periodo_id' => $periodoId]);
        return $stmt->fetch();
    }

    /**
     * Generar archivo PLE 14.1
     */
    public function generarPLE($periodoId, $empresaId, $periodo)
    {
        $ventas = $this->getByPeriodo($periodoId);
        $contenido = "";

        foreach ($ventas as $venta) {
            $linea = [
                $periodo['anio'] . str_pad($periodo['mes'], 2, '0', STR_PAD_LEFT) . '00', // Período
                str_pad($venta['correlativo'], 10, '0', STR_PAD_LEFT), // Correlativo
                date('d/m/Y', strtotime($venta['fecha_emision'])), // Fecha emisión
                $venta['fecha_vencimiento'] ? date('d/m/Y', strtotime($venta['fecha_vencimiento'])) : '', // Fecha vencimiento
                $venta['tipo_comprobante'], // Tipo comprobante
                $venta['serie'], // Serie
                $venta['numero'], // Número
                '', // Número final (rango)
                $venta['tipo_documento_cliente'], // Tipo doc cliente
                $venta['numero_documento_cliente'], // Num doc cliente
                $venta['razon_social_cliente'], // Razón social
                number_format($venta['valor_exportacion'], 2, '.', ''), // Valor exportación
                number_format($venta['base_imponible'], 2, '.', ''), // Base imponible
                number_format($venta['igv'], 2, '.', ''), // IGV
                number_format($venta['exonerado'], 2, '.', ''), // Exonerado
                number_format($venta['inafecto'], 2, '.', ''), // Inafecto
                number_format($venta['isc'], 2, '.', ''), // ISC
                number_format($venta['otros_tributos'], 2, '.', ''), // Otros tributos
                number_format($venta['importe_total'], 2, '.', ''), // Importe total
                number_format($venta['tipo_cambio'], 4, '.', ''), // Tipo cambio
                $venta['fecha_emision_modificado'] ? date('d/m/Y', strtotime($venta['fecha_emision_modificado'])) : '', // Fecha emisión modificado
                $venta['tipo_comprobante_modificado'] ?? '', // Tipo comprobante modificado
                $venta['serie_modificado'] ?? '', // Serie modificado
                $venta['numero_modificado'] ?? '', // Número modificado
                '1' // Estado (1 = registrado)
            ];

            $contenido .= implode('|', $linea) . "\r\n";
        }

        return $contenido;
    }
}
