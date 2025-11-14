<?php

namespace Core;

/**
 * Router del sistema
 */
class Router
{
    private $routes = [];
    private $params = [];

    /**
     * Registrar ruta GET
     */
    public function get($route, $handler)
    {
        $this->addRoute('GET', $route, $handler);
    }

    /**
     * Registrar ruta POST
     */
    public function post($route, $handler)
    {
        $this->addRoute('POST', $route, $handler);
    }

    /**
     * Agregar ruta
     */
    private function addRoute($method, $route, $handler)
    {
        $route = trim($route, '/');
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'handler' => $handler
        ];
    }

    /**
     * Despachar la ruta solicitada
     */
    public function dispatch($url, Request $request)
    {
        $url = trim($url, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->convertToRegex($route['route']);

            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches); // Remover el match completo
                $this->params = $matches;

                return $this->callHandler($route['handler'], $request);
            }
        }

        // No se encontró la ruta - error 404
        $this->handleNotFound();
    }

    /**
     * Convertir ruta a expresión regular
     */
    private function convertToRegex($route)
    {
        // Escapar barras
        $route = str_replace('/', '\/', $route);

        // Convertir {param} a grupos de captura
        $route = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $route);

        return '/^' . $route . '$/';
    }

    /**
     * Llamar al handler
     */
    private function callHandler($handler, Request $request)
    {
        list($controller, $method) = explode('@', $handler);

        $controllerClass = 'Controllers\\' . $controller;

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} not found");
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $method)) {
            throw new \Exception("Method {$method} not found in {$controllerClass}");
        }

        // Pasar los parámetros al método
        return call_user_func_array(
            [$controllerInstance, $method],
            array_merge([$request], $this->params)
        );
    }

    /**
     * Manejar ruta no encontrada
     */
    private function handleNotFound()
    {
        header("HTTP/1.0 404 Not Found");
        if (file_exists(APP . 'views' . DS . 'errors' . DS . '404.php')) {
            include APP . 'views' . DS . 'errors' . DS . '404.php';
        } else {
            echo "<h1>404 - Página no encontrada</h1>";
        }
        exit;
    }
}
