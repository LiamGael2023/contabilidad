<?php
/**
 * Punto de entrada del sistema de contabilidad
 */

// Establecer zona horaria de Perú
date_default_timezone_set('America/Lima');

// Definir constantes
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__) . DS);
define('APP', ROOT . 'app' . DS);
define('CONFIG', ROOT . 'config' . DS);
define('SRC', ROOT . 'src' . DS);
define('PUBLIC_PATH', ROOT . 'public' . DS);
define('STORAGE', ROOT . 'storage' . DS);

// Iniciar sesión
session_start();

// Autoloader
require_once SRC . 'Core' . DS . 'Autoloader.php';

use Core\Autoloader;
use Core\Router;
use Core\Request;

// Registrar autoloader
Autoloader::register();

// Cargar configuración
$config = require_once CONFIG . 'app.php';

// Manejo de errores
if ($config['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Crear instancia del router
$router = new Router();

// Cargar rutas
require_once ROOT . 'routes.php';

// Obtener la URL solicitada
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Crear request
$request = new Request();

// Ejecutar el router
try {
    $router->dispatch($url, $request);
} catch (Exception $e) {
    if ($config['debug']) {
        echo "<h1>Error</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        header("HTTP/1.0 500 Internal Server Error");
        include APP . 'views' . DS . 'errors' . DS . '500.php';
    }
}
