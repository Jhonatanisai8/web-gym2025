<?php

class UsuariosController extends Controller
{
    private $usuarioModel;

    public function __construct()
    {
        $this->requireAdmin();
        $this->usuarioModel = $this->model('Usuario');
    }

    public function index()
    {
        $usuarios = $this->usuarioModel->getAllWithRole();
        $data = [
            'title' => 'Usuarios',
            'usuarios' => $usuarios
        ];
        $this->view('usuarios/index', $data);
    }

    public function cambiarEstado($id)
    {
        $estado = $this->getGet('estado', 'activo');
        $this->usuarioModel->changeStatus($id, $estado);
        $_SESSION['success'] = 'Estado actualizado';
        $this->redirect('usuarios');
    }
}

