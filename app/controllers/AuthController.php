<?php

/**
 * Controlador de Autenticación
 */
class AuthController extends Controller
{

    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = $this->model('Usuario');
    }

    /**
     * Muestra el formulario de login
     */
    public function login()
    {
        // Si ya está autenticado, redirigir al dashboard
        if ($this->isAuthenticated()) {
            $this->redirect('dashboard');
        }

        $data = [
            'title' => 'Iniciar Sesión',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->getPost('email');
            $password = $this->getPost('password');

            // Validar campos
            if (empty($email) || empty($password)) {
                $data['error'] = 'Por favor complete todos los campos';
            } else {
                // Intentar autenticar
                $user = $this->usuarioModel->authenticate($email, $password);

                if ($user) {
                    // Crear sesión
                    $this->createSession($user);
                    $this->redirect('dashboard');
                } else {
                    $data['error'] = 'Email o contraseña incorrectos';
                }
            }
        }

        $this->view('auth/login', $data);
    }

    /**
     * Cierra la sesión
     */
    public function logout()
    {
        $this->destroySession();
        $this->redirect('auth/login');
    }

    /**
     * Crea la sesión del usuario
     */
    private function createSession($user)
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nombre'] = $user['nombre'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['rol_id'] = $user['rol_id'];
        $_SESSION['rol_nombre'] = $user['rol_nombre'];
        $_SESSION['user_foto'] = $user['foto'];
    }

    /**
     * Destruye la sesión
     */
    private function destroySession()
    {
        session_unset();
        session_destroy();
    }
}
