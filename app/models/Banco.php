<?php

namespace Models;

use Core\Model;

class Banco extends Model
{
    protected $table = 'bancos';
    protected $fillable = [
        'empresa_id', 'nombre_banco', 'tipo_cuenta', 'numero_cuenta',
        'moneda', 'saldo_inicial', 'saldo_actual', 'activo'
    ];

    /**
     * Obtener bancos activos por empresa
     */
    public function getByEmpresa($empresaId)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE empresa_id = :empresa_id
                AND activo = 1
                ORDER BY nombre_banco, numero_cuenta";

        $stmt = $this->db->query($sql, ['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener saldo total en soles
     */
    public function getSaldoTotalSoles($empresaId)
    {
        $sql = "SELECT SUM(saldo_actual) as total
                FROM {$this->table}
                WHERE empresa_id = :empresa_id
                AND activo = 1
                AND moneda = 'PEN'";

        $stmt = $this->db->query($sql, ['empresa_id' => $empresaId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Obtener saldo total en dólares
     */
    public function getSaldoTotalDolares($empresaId)
    {
        $sql = "SELECT SUM(saldo_actual) as total
                FROM {$this->table}
                WHERE empresa_id = :empresa_id
                AND activo = 1
                AND moneda = 'USD'";

        $stmt = $this->db->query($sql, ['empresa_id' => $empresaId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Actualizar saldo
     */
    public function actualizarSaldo($id, $monto, $tipo = 'ingreso')
    {
        $banco = $this->find($id);
        if (!$banco) {
            throw new \Exception('Cuenta bancaria no encontrada');
        }

        $nuevoSaldo = $tipo === 'ingreso'
            ? $banco['saldo_actual'] + $monto
            : $banco['saldo_actual'] - $monto;

        if ($nuevoSaldo < 0) {
            throw new \Exception('Saldo insuficiente');
        }

        $this->update($id, ['saldo_actual' => $nuevoSaldo]);
        return $nuevoSaldo;
    }
}
