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
        // Reemplazar namespace separators con directory separators
        $class = str_replace('\\', DS, $class);

        // Buscar en diferentes directorios
        $paths = [
            SRC . $class . '.php',
            APP . 'controllers' . DS . $class . '.php',
            APP . 'models' . DS . $class . '.php',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                require_once $path;
                return;
            }
        }
    }
}
