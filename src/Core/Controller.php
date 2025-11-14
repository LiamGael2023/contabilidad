<?php

namespace Core;

/**
 * Controlador base
 */
class Controller
{
    protected $config;

    public function __construct()
    {
        $this->config = require CONFIG . 'app.php';
    }

    /**
     * Renderizar vista
     */
    protected function view($view, $data = [])
    {
        extract($data);

        $viewFile = APP . 'views' . DS . str_replace('.', DS, $view) . '.php';

        if (!file_exists($viewFile)) {
            throw new \Exception("View {$view} not found");
        }

        // Cargar layout si existe
        $useLayout = !isset($noLayout) || !$noLayout;

        if ($useLayout) {
            $content = $this->renderPartial($viewFile, $data);
            include APP . 'views' . DS . 'layouts' . DS . 'main.php';
        } else {
            include $viewFile;
        }
    }

    /**
     * Renderizar vista parcial
     */
    protected function renderPartial($viewFile, $data = [])
    {
        extract($data);
        ob_start();
        include $viewFile;
        return ob_get_clean();
    }

    /**
     * Redireccionar
     */
    protected function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $this->url($url), true, $statusCode);
        exit;
    }

    /**
     * Generar URL
     */
    protected function url($path = '')
    {
        $baseUrl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $baseUrl .= '://' . $_SERVER['HTTP_HOST'];
        return $baseUrl . '/' . ltrim($path, '/');
    }

    /**
     * Responder con JSON
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Verificar autenticación
     */
    protected function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }

    /**
     * Obtener usuario actual
     */
    protected function currentUser()
    {
        return isset($_SESSION['user_id']) ? $_SESSION['user'] : null;
    }

    /**
     * Establecer mensaje flash
     */
    protected function setFlash($type, $message)
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Obtener mensaje flash
     */
    protected function getFlash()
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Generar token CSRF
     */
    protected function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
