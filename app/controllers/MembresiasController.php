<?php

class MembresiasController extends Controller
{
    private $membresiaModel;

    public function __construct()
    {
        $this->requireAdmin();
        $this->membresiaModel = $this->model('Membresia');
    }

    public function index()
    {
        $membresias = $this->membresiaModel->getAll();
        $data = [
            'title' => 'Membresías',
            'membresias' => $membresias
        ];
        $this->view('membresias/index', $data);
    }

    public function crear()
    {
        $data = [
            'title' => 'Nueva Membresía',
            'errors' => [],
            'old' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $this->getPost('nombre');
            $duracion = (int)$this->getPost('duracion_dias');
            $precio = $this->getPost('precio');

            $errors = [];
            if (!$nombre) $errors[] = 'El nombre es obligatorio';
            if ($duracion <= 0) $errors[] = 'La duración debe ser mayor que 0';
            if ($precio === '' || !is_numeric($precio)) $errors[] = 'El precio es inválido';

            if (empty($errors)) {
                $ok = $this->membresiaModel->insert([
                    'nombre' => $nombre,
                    'descripcion' => $this->getPost('descripcion'),
                    'duracion_dias' => $duracion,
                    'precio' => $precio,
                    'estado' => 'activo'
                ]);
                if ($ok) {
                    $_SESSION['success'] = 'Membresía creada';
                    $this->redirect('membresias');
                } else {
                    $errors[] = 'Error al crear la membresía';
                }
            }

            $data['errors'] = $errors;
            $data['old'] = $_POST;
        }

        $this->view('membresias/crear', $data);
    }

    public function editar($id)
    {
        $membresia = $this->membresiaModel->getById($id);
        if (!$membresia) {
            $_SESSION['error'] = 'Membresía no encontrada';
            $this->redirect('membresias');
        }

        $data = [
            'title' => 'Editar Membresía',
            'membresia' => $membresia,
            'errors' => [],
            'old' => $membresia
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $this->getPost('nombre');
            $duracion = (int)$this->getPost('duracion_dias');
            $precio = $this->getPost('precio');
            $errors = [];
            if (!$nombre) $errors[] = 'El nombre es obligatorio';
            if ($duracion <= 0) $errors[] = 'La duración debe ser mayor que 0';
            if ($precio === '' || !is_numeric($precio)) $errors[] = 'El precio es inválido';

            if (empty($errors)) {
                $ok = $this->membresiaModel->update($id, [
                    'nombre' => $nombre,
                    'descripcion' => $this->getPost('descripcion'),
                    'duracion_dias' => $duracion,
                    'precio' => $precio
                ]);
                if ($ok) {
                    $_SESSION['success'] = 'Membresía actualizada';
                    $this->redirect('membresias');
                } else {
                    $errors[] = 'Error al actualizar';
                }
            }

            $data['errors'] = $errors;
            $data['old'] = $_POST;
        }

        $this->view('membresias/editar', $data);
    }

    public function cambiarEstado($id)
    {
        $m = $this->membresiaModel->getById($id);
        if ($m) {
            $nuevo = $m['estado'] === 'activo' ? 'inactivo' : 'activo';
            $this->membresiaModel->update($id, ['estado' => $nuevo]);
            $_SESSION['success'] = 'Estado actualizado';
        }
        $this->redirect('membresias');
    }
}

