<?php

/**
 * Controlador de Clientes
 */
class ClientesController extends Controller
{

    private $clienteModel;

    public function __construct()
    {
        $this->requireAuth();
        $this->clienteModel = $this->model('Cliente');
    }

    /**
     * Lista todos los clientes
     */
    public function index()
    {
        $page = (int)$this->getGet('page', 1);
        if ($page < 1) {
            $page = 1;
        }
        $perPage = 10;
        $total = (int)$this->clienteModel->count();
        $totalPages = max(1, (int)ceil($total / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $perPage;
        $clientes = $this->clienteModel->getPaginatedWithMembership($perPage, $offset);

        $data = [
            'title' => 'Gestión de Clientes',
            'clientes' => $clientes,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages
        ];

        $this->view('clientes/index', $data);
    }

    /**
     * Muestra el formulario para crear un nuevo cliente
     */
    public function crear()
    {
        $data = [
            'title' => 'Nuevo Cliente',
            'errors' => [],
            'old' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateCliente();

            if (empty($errors)) {
                $clienteData = [
                    'dni' => $this->getPost('dni'),
                    'nombre' => $this->getPost('nombre'),
                    'apellido' => $this->getPost('apellido'),
                    'email' => $this->getPost('email'),
                    'telefono' => $this->getPost('telefono'),
                    'direccion' => $this->getPost('direccion'),
                    'fecha_nacimiento' => $this->getPost('fecha_nacimiento') ?: null,
                    'estado' => 'activo'
                ];

                // Manejar foto si se subió
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
                    $foto = $this->uploadFoto($_FILES['foto']);
                    if ($foto) {
                        $clienteData['foto'] = $foto;
                    }
                }

                if ($this->clienteModel->insert($clienteData)) {
                    $_SESSION['success'] = 'Cliente registrado exitosamente';
                    $this->redirect('clientes');
                } else {
                    $errors[] = 'Error al guardar el cliente';
                }
            }

            $data['errors'] = $errors;
            $data['old'] = $_POST;
        }

        $this->view('clientes/crear', $data);
    }

    /**
     * Muestra el formulario para editar un cliente
     */
    public function editar($id)
    {
        $cliente = $this->clienteModel->getById($id);

        if (!$cliente) {
            $_SESSION['error'] = 'Cliente no encontrado';
            $this->redirect('clientes');
        }

        $data = [
            'title' => 'Editar Cliente',
            'cliente' => $cliente,
            'errors' => [],
            'old' => $cliente
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateCliente($id);

            if (empty($errors)) {
                $clienteData = [
                    'dni' => $this->getPost('dni'),
                    'nombre' => $this->getPost('nombre'),
                    'apellido' => $this->getPost('apellido'),
                    'email' => $this->getPost('email'),
                    'telefono' => $this->getPost('telefono'),
                    'direccion' => $this->getPost('direccion'),
                    'fecha_nacimiento' => $this->getPost('fecha_nacimiento') ?: null
                ];

                // Manejar foto si se subió
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
                    $foto = $this->uploadFoto($_FILES['foto']);
                    if ($foto) {
                        // Eliminar foto anterior si existe
                        if (!empty($cliente['foto'])) {
                            $this->deleteFoto($cliente['foto']);
                        }
                        $clienteData['foto'] = $foto;
                    }
                }

                if ($this->clienteModel->update($id, $clienteData)) {
                    $_SESSION['success'] = 'Cliente actualizado exitosamente';
                    $this->redirect('clientes');
                } else {
                    $errors[] = 'Error al actualizar el cliente';
                }
            }

            $data['errors'] = $errors;
            $data['old'] = $_POST;
        }

        $this->view('clientes/editar', $data);
    }

    /**
     * Ver detalles de un cliente
     */
    public function ver($id)
    {
        $cliente = $this->clienteModel->getById($id);

        if (!$cliente) {
            $_SESSION['error'] = 'Cliente no encontrado';
            $this->redirect('clientes');
        }

        $membresiaActiva = $this->clienteModel->getMembresiaActiva($id);
        $asistencias = $this->clienteModel->getAsistencias($id, 10);

        $data = [
            'title' => 'Detalles del Cliente',
            'cliente' => $cliente,
            'membresiaActiva' => $membresiaActiva,
            'asistencias' => $asistencias
        ];

        $this->view('clientes/ver', $data);
    }

    /**
     * Cambia el estado de un cliente
     */
    public function cambiarEstado($id)
    {
        $cliente = $this->clienteModel->getById($id);

        if ($cliente) {
            $nuevoEstado = $cliente['estado'] === 'activo' ? 'inactivo' : 'activo';
            $this->clienteModel->update($id, ['estado' => $nuevoEstado]);
            $_SESSION['success'] = 'Estado del cliente actualizado';
        }

        $this->redirect('clientes');
    }

    /**
     * Buscar clientes (AJAX)
     */
    public function buscar()
    {
        $term = $this->getGet('q', '');

        if (strlen($term) < 2) {
            $this->json(['results' => []]);
        }

        $clientes = $this->clienteModel->search($term);
        $this->json(['results' => $clientes]);
    }

    /**
     * Valida los datos del cliente
     */
    private function validateCliente($excludeId = null)
    {
        $errors = [];

        $dni = $this->getPost('dni');
        $nombre = $this->getPost('nombre');
        $apellido = $this->getPost('apellido');

        if (empty($dni)) {
            $errors[] = 'El DNI es obligatorio';
        } elseif ($this->clienteModel->dniExists($dni, $excludeId)) {
            $errors[] = 'El DNI ya está registrado';
        }

        if (empty($nombre)) {
            $errors[] = 'El nombre es obligatorio';
        }

        if (empty($apellido)) {
            $errors[] = 'El apellido es obligatorio';
        }

        return $errors;
    }

    /**
     * Sube una foto
     */
    private function uploadFoto($file)
    {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            return false;
        }

        $filename = uniqid() . '.' . $ext;
        $destination = UPLOAD_PATH . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        }

        return false;
    }

    /**
     * Elimina una foto
     */
    private function deleteFoto($filename)
    {
        $path = UPLOAD_PATH . $filename;
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
