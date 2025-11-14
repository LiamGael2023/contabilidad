<?php

namespace Models;

use Core\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $fillable = ['username', 'password', 'nombre', 'apellido', 'email', 'rol', 'activo'];

    /**
     * Buscar usuario por username
     */
    public function findByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username AND activo = 1 LIMIT 1";
        $stmt = $this->db->query($sql, ['username' => $username]);
        return $stmt->fetch();
    }

    /**
     * Verificar contraseña
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Hash de contraseña
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Crear usuario
     */
    public function createUser($data)
    {
        $data['password'] = $this->hashPassword($data['password']);
        return $this->create($data);
    }

    /**
     * Obtener usuarios activos
     */
    public function getActiveUsers()
    {
        return $this->where('activo', 1);
    }
}
