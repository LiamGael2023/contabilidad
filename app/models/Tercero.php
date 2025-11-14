<?php

namespace Models;

use Core\Model;

class Tercero extends Model
{
    protected $table = 'terceros';
    protected $fillable = [
        'empresa_id', 'tipo_documento', 'numero_documento',
        'razon_social', 'nombre_comercial', 'direccion', 'ubigeo',
        'telefono', 'email', 'tipo_tercero', 'activo'
    ];

    /**
     * Obtener terceros por empresa
     */
    public function getByEmpresa($empresaId, $tipo = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE empresa_id = :empresa_id AND activo = 1";
        $params = ['empresa_id' => $empresaId];

        if ($tipo) {
            $sql .= " AND (tipo_tercero = :tipo OR tipo_tercero = 'ambos')";
            $params['tipo'] = $tipo;
        }

        $sql .= " ORDER BY razon_social";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Buscar por documento
     */
    public function findByDocumento($empresaId, $tipoDocumento, $numeroDocumento)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE empresa_id = :empresa_id
                AND tipo_documento = :tipo_documento
                AND numero_documento = :numero_documento
                LIMIT 1";
        $stmt = $this->db->query($sql, [
            'empresa_id' => $empresaId,
            'tipo_documento' => $tipoDocumento,
            'numero_documento' => $numeroDocumento
        ]);
        return $stmt->fetch();
    }

    /**
     * Obtener clientes
     */
    public function getClientes($empresaId)
    {
        return $this->getByEmpresa($empresaId, 'cliente');
    }

    /**
     * Obtener proveedores
     */
    public function getProveedores($empresaId)
    {
        return $this->getByEmpresa($empresaId, 'proveedor');
    }
}
