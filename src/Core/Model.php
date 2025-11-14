<?php

namespace Core;

/**
 * Modelo base
 */
class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $timestamps = true;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Obtener todos los registros
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Buscar por ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Buscar por criterios
     */
    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value";
        return $this->db->query($sql, ['value' => $value])->fetchAll();
    }

    /**
     * Crear registro
     */
    public function create($data)
    {
        $data = $this->filterFillable($data);

        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $this->db->query($sql, $data);
        return $this->db->lastInsertId();
    }

    /**
     * Actualizar registro
     */
    public function update($id, $data)
    {
        $data = $this->filterFillable($data);

        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setParts);

        $data['id'] = $id;

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";

        return $this->db->query($sql, $data);
    }

    /**
     * Eliminar registro
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->query($sql, ['id' => $id]);
    }

    /**
     * Contar registros
     */
    public function count($where = null)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";

        if ($where) {
            $sql .= " WHERE " . $where;
        }

        $result = $this->db->query($sql)->fetch();
        return $result['total'];
    }

    /**
     * Filtrar datos según fillable
     */
    private function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }

        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Ejecutar query personalizado
     */
    public function query($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }
}
