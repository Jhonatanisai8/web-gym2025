<?php

/**
 * Configuración general del sistema
 */

// URL base del proyecto
define('BASE_URL', 'http://localhost/web-gym/');

// Rutas del sistema
define('ROOT_PATH', dirname(__DIR__) . '/');
define('APP_PATH', ROOT_PATH . 'app/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('UPLOAD_PATH', PUBLIC_PATH . 'uploads/');

// Configuración de sesión
define('SESSION_NAME', 'GIMNASIO_SESSION');
define('SESSION_LIFETIME', 7200); // 2 horas

// Configuración de la aplicación
define('APP_NAME', 'Sistema de Gestión - Gimnasio');
define('APP_VERSION', '1.0.0');

// Zona horaria
date_default_timezone_set('America/Lima');

// Configuración de errores (cambiar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoloader simple
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . 'models/' . $class . '.php',
        APP_PATH . 'controllers/' . $class . '.php',
        CONFIG_PATH . $class . '.php'
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Incluir archivo de base de datos
require_once CONFIG_PATH . 'database.php';
