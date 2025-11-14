<?php
/**
 * Configuración general de la aplicación
 */

return [
    'app_name' => 'Sistema de Contabilidad Perú',
    'version' => '1.0.0',
    'debug' => true, // Cambiar a false en producción

    // Configuración de zona horaria
    'timezone' => 'America/Lima',

    // Configuración de moneda
    'currency' => 'PEN',
    'currency_symbol' => 'S/',

    // Configuración regional Perú
    'country' => 'PE',
    'tax_name' => 'IGV',
    'tax_rate' => 0.18, // 18% IGV

    // Configuración de empresa por defecto
    'default_language' => 'es',

    // Configuración de PLE
    'ple' => [
        'separator' => '|',
        'encoding' => 'UTF-8',
        'line_ending' => "\r\n"
    ],

    // Configuración de reportes
    'reports' => [
        'date_format' => 'd/m/Y',
        'datetime_format' => 'd/m/Y H:i:s',
        'decimal_separator' => '.',
        'thousands_separator' => ',',
        'decimal_places' => 2
    ],

    // Configuración de sesión
    'session' => [
        'lifetime' => 7200, // 2 horas
        'path' => '/',
        'secure' => false, // true si usas HTTPS
        'httponly' => true
    ],

    // Configuración de seguridad
    'security' => [
        'password_min_length' => 8,
        'password_hash_algo' => PASSWORD_BCRYPT,
        'csrf_token_name' => 'csrf_token',
        'csrf_token_time' => 3600
    ]
];
