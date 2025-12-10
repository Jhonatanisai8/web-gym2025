<?php

/**
 * Archivo principal - Router del sistema
 */

// Cargar configuración
require_once 'config/config.php';

// Iniciar sesión
session_name(SESSION_NAME);
session_start();

// Obtener la URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'auth/login';
$url = explode('/', filter_var($url, FILTER_SANITIZE_URL));

// Determinar controlador
$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'AuthController';
$controllerFile = APP_PATH . 'controllers/' . $controllerName . '.php';

// Verificar si existe el controlador
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName;

    // Determinar método
    $method = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';

    // Verificar si existe el método
    if (method_exists($controller, $method)) {
        // Obtener parámetros
        $params = array_slice($url, 2);

        // Llamar al método con parámetros
        call_user_func_array([$controller, $method], $params);
    } else {
        // Método no encontrado
        http_response_code(404);
        echo "Método no encontrado: {$method}";
    }
} else {
    // Controlador no encontrado
    http_response_code(404);
    echo "Controlador no encontrado: {$controllerName}";
}
