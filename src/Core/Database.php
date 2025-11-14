<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Clase para manejo de base de datos (Singleton)
 */
class Database
{
    private static $instance = null;
    private $pdo;
    private $config;

    private function __construct()
    {
        $this->config = require CONFIG . 'database.php';
        $this->connect();
    }

    /**
     * Obtener instancia única
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Conectar a la base de datos
     */
    private function connect()
    {
        $dsn = sprintf(
            "%s:host=%s;port=%d;dbname=%s;charset=%s",
            $this->config['driver'],
            $this->config['host'],
            $this->config['port'],
            $this->config['database'],
            $this->config['charset']
        );

        try {
            $this->pdo = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $this->config['options']
            );
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    /**
     * Ejecutar query
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new \Exception("Error en query: " . $e->getMessage());
        }
    }

    /**
     * Obtener último ID insertado
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Iniciar transacción
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Confirmar transacción
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Revertir transacción
     */
    public function rollback()
    {
        return $this->pdo->rollback();
    }

    /**
     * Obtener conexión PDO
     */
    public function getConnection()
    {
        return $this->pdo;
    }
}
