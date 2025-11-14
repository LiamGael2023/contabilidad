<?php

namespace Core;

/**
 * Clase para manejar peticiones HTTP
 */
class Request
{
    private $get;
    private $post;
    private $files;
    private $server;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }

    /**
     * Obtener método HTTP
     */
    public function method()
    {
        return $this->server['REQUEST_METHOD'];
    }

    /**
     * Verificar si es GET
     */
    public function isGet()
    {
        return $this->method() === 'GET';
    }

    /**
     * Verificar si es POST
     */
    public function isPost()
    {
        return $this->method() === 'POST';
    }

    /**
     * Obtener parámetro GET
     */
    public function get($key = null, $default = null)
    {
        if ($key === null) {
            return $this->get;
        }
        return isset($this->get[$key]) ? $this->sanitize($this->get[$key]) : $default;
    }

    /**
     * Obtener parámetro POST
     */
    public function post($key = null, $default = null)
    {
        if ($key === null) {
            return $this->post;
        }
        return isset($this->post[$key]) ? $this->sanitize($this->post[$key]) : $default;
    }

    /**
     * Obtener archivo subido
     */
    public function file($key)
    {
        return isset($this->files[$key]) ? $this->files[$key] : null;
    }

    /**
     * Obtener todos los datos (GET + POST)
     */
    public function all()
    {
        return array_merge($this->get, $this->post);
    }

    /**
     * Sanitizar entrada
     */
    private function sanitize($value)
    {
        if (is_array($value)) {
            return array_map([$this, 'sanitize'], $value);
        }
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validar token CSRF
     */
    public function validateCsrfToken()
    {
        $token = $this->post('csrf_token');
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
