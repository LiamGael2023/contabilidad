<?php

namespace Core;

/**
 * Autoloader de clases
 */
class Autoloader
{
    public static function register()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    public static function autoload($class)
    {
        // Separar namespace de la clase
        $parts = explode('\\', $class);
        $className = array_pop($parts);
        $namespace = implode('\\', $parts);

        // Mapeo de namespaces a directorios
        $paths = [];

        // Core namespace -> src/Core
        if ($namespace === 'Core') {
            $paths[] = SRC . 'Core' . DS . $className . '.php';
        }

        // Helpers namespace -> src/Helpers
        if ($namespace === 'Helpers') {
            $paths[] = SRC . 'Helpers' . DS . $className . '.php';
        }

        // Controllers namespace -> app/controllers
        if ($namespace === 'Controllers') {
            $paths[] = APP . 'controllers' . DS . $className . '.php';
        }

        // Models namespace -> app/models
        if ($namespace === 'Models') {
            $paths[] = APP . 'models' . DS . $className . '.php';
        }

        // Intentar cargar el archivo
        foreach ($paths as $path) {
            if (file_exists($path)) {
                require_once $path;
                return;
            }
        }
    }
}
