<?php

/**
 * Controlador base
 * Todos los controladores heredan de esta clase
 */
class Controller
{

    /**
     * Carga un modelo
     */
    protected function model($model)
    {
        $modelPath = APP_PATH . 'models/' . $model . '.php';

        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("El modelo {$model} no existe");
        }
    }

    /**
     * Carga una vista
     */
    protected function view($view, $data = [])
    {
        $viewPath = APP_PATH . 'views/' . $view . '.php';

        if (file_exists($viewPath)) {
            extract($data);
            require_once $viewPath;
        } else {
            die("La vista {$view} no existe");
        }
    }

    /**
     * Redirecciona a una URL
     */
    protected function redirect($url)
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    /**
     * Verifica si el usuario está autenticado
     */
    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Verifica si el usuario tiene un rol específico
     */
    protected function hasRole($roleName)
    {
        return isset($_SESSION['rol_nombre']) && $_SESSION['rol_nombre'] === $roleName;
    }

    /**
     * Requiere autenticación
     */
    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Requiere rol de administrador
     */
    protected function requireAdmin()
    {
        $this->requireAuth();
        if (!$this->hasRole('Administrador')) {
            $this->redirect('dashboard');
        }
    }

    /**
     * Obtiene datos POST de forma segura
     */
    protected function getPost($key, $default = '')
    {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    /**
     * Obtiene datos GET de forma segura
     */
    protected function getGet($key, $default = '')
    {
        return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
    }

    /**
     * Sanitiza datos
     */
    protected function sanitize($data)
    {
        return htmlspecialchars(strip_tags($data), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Retorna JSON
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
