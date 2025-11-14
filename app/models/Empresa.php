<?php

namespace Models;

use Core\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $fillable = [
        'ruc', 'razon_social', 'nombre_comercial', 'direccion',
        'ubigeo', 'departamento', 'provincia', 'distrito',
        'telefono', 'email', 'actividad_economica', 'regimen_tributario',
        'tipo_contribuyente', 'fecha_inicio_actividades',
        'representante_legal', 'dni_representante', 'activo'
    ];

    /**
     * Obtener empresas activas
     */
    public function getActiveEmpresas()
    {
        return $this->where('activo', 1);
    }

    /**
     * Buscar por RUC
     */
    public function findByRuc($ruc)
    {
        $sql = "SELECT * FROM {$this->table} WHERE ruc = :ruc LIMIT 1";
        $stmt = $this->db->query($sql, ['ruc' => $ruc]);
        return $stmt->fetch();
    }

    /**
     * Validar RUC (11 dígitos)
     */
    public function validarRuc($ruc)
    {
        return preg_match('/^\d{11}$/', $ruc);
    }
}
