<?php

namespace Models;

use Core\Model;

class RegistroCompra extends Model
{
    protected $table = 'registro_compras';
    protected $fillable = [
        'empresa_id', 'periodo_id', 'correlativo', 'fecha_emision',
        'fecha_vencimiento', 'tipo_comprobante', 'serie', 'numero',
        'tipo_documento_proveedor', 'numero_documento_proveedor', 'razon_social_proveedor',
        'base_imponible', 'igv', 'exonerado', 'inafecto',
        'isc', 'otros_tributos', 'importe_total', 'tipo_cambio', 'moneda',
        'fecha_emision_modificado', 'tipo_comprobante_modificado',
        'serie_modificado', 'numero_modificado', 'tipo_compra', 'estado'
    ];

    /**
     * Obtener compras por período
     */
    public function getByPeriodo($periodoId)
    {
        $sql = "SELECT rc.*, t.razon_social as proveedor_nombre
                FROM {$this->table} rc
                LEFT JOIN terceros t ON rc.numero_documento_proveedor COLLATE utf8mb4_general_ci = t.numero_documento COLLATE utf8mb4_general_ci
                WHERE rc.periodo_id = :periodo_id
                ORDER BY rc.fecha_emision DESC, rc.correlativo DESC";

        $stmt = $this->db->query($sql, ['periodo_id' => $periodoId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener compras por rango de fechas
     */
    public function getByFechas($empresaId, $fechaInicio, $fechaFin)
    {
        $sql = "SELECT rc.*, t.razon_social as proveedor_nombre
                FROM {$this->table} rc
                LEFT JOIN terceros t ON rc.numero_documento_proveedor COLLATE utf8mb4_general_ci = t.numero_documento COLLATE utf8mb4_general_ci
                WHERE rc.empresa_id = :empresa_id
                AND rc.fecha_emision BETWEEN :fecha_inicio AND :fecha_fin
                ORDER BY rc.fecha_emision, rc.correlativo";

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
     * Generar archivo PLE 8.1
     */
    public function generarPLE($periodoId, $empresaId, $periodo)
    {
        $compras = $this->getByPeriodo($periodoId);
        $contenido = "";

        foreach ($compras as $compra) {
            $linea = [
                $periodo['anio'] . str_pad($periodo['mes'], 2, '0', STR_PAD_LEFT) . '00', // Período
                str_pad($compra['correlativo'], 10, '0', STR_PAD_LEFT), // Correlativo
                date('d/m/Y', strtotime($compra['fecha_emision'])), // Fecha emisión
                $compra['fecha_vencimiento'] ? date('d/m/Y', strtotime($compra['fecha_vencimiento'])) : '', // Fecha vencimiento
                $compra['tipo_comprobante'], // Tipo comprobante
                $compra['serie'], // Serie
                $compra['numero'], // Número
                '', // Número final (rango)
                $compra['tipo_documento_proveedor'], // Tipo doc proveedor
                $compra['numero_documento_proveedor'], // Num doc proveedor
                $compra['razon_social_proveedor'], // Razón social
                number_format($compra['base_imponible'], 2, '.', ''), // Base imponible
                number_format($compra['igv'], 2, '.', ''), // IGV
                number_format($compra['exonerado'], 2, '.', ''), // Exonerado
                number_format($compra['inafecto'], 2, '.', ''), // Inafecto
                number_format($compra['isc'], 2, '.', ''), // ISC
                number_format($compra['otros_tributos'], 2, '.', ''), // Otros tributos
                number_format($compra['importe_total'], 2, '.', ''), // Importe total
                number_format($compra['tipo_cambio'], 4, '.', ''), // Tipo cambio
                $compra['fecha_emision_modificado'] ? date('d/m/Y', strtotime($compra['fecha_emision_modificado'])) : '', // Fecha emisión modificado
                $compra['tipo_comprobante_modificado'] ?? '', // Tipo comprobante modificado
                $compra['serie_modificado'] ?? '', // Serie modificado
                $compra['numero_modificado'] ?? '', // Número modificado
                '1' // Estado (1 = registrado)
            ];

            $contenido .= implode('|', $linea) . "\r\n";
        }

        return $contenido;
    }
}
