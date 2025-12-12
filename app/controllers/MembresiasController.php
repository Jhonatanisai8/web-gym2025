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

    public function asignar($clienteId)
    {
        // Verificar que el cliente existe
        $clienteModel = $this->model('Cliente');
        $cliente = $clienteModel->getById($clienteId);

        if (!$cliente) {
            $_SESSION['error'] = 'Cliente no encontrado';
            $this->redirect('clientes');
        }

        // Obtener todas las membresías activas
        $membresias = $this->membresiaModel->query(
            "SELECT * FROM membresias WHERE estado = 'activo' ORDER BY nombre"
        )->fetchAll();

        $data = [
            'title' => 'Asignar Membresía',
            'cliente' => $cliente,
            'membresias' => $membresias,
            'errors' => [],
            'old' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $membresiaId = (int)$this->getPost('membresia_id');
            $fechaInicio = $this->getPost('fecha_inicio');
            $metodoPago = $this->getPost('metodo_pago');

            $errors = [];
            if (!$membresiaId) $errors[] = 'Debe seleccionar una membresía';
            if (!$fechaInicio) $errors[] = 'La fecha de inicio es obligatoria';
            if (!$metodoPago) $errors[] = 'El método de pago es obligatorio';

            if (empty($errors)) {
                // Obtener la membresía seleccionada
                $membresia = $this->membresiaModel->getById($membresiaId);

                if ($membresia) {
                    try {

                        // Calcular fecha de fin
                        $inicio = new DateTime($fechaInicio);
                        $fin = clone $inicio;
                        $fin->modify('+' . $membresia['duracion_dias'] . ' days');

                        // Desactivar membresías anteriores del cliente
                        $this->membresiaModel->query(
                            "UPDATE cliente_membresias SET estado = 'vencida' 
                             WHERE cliente_id = ? AND estado = 'activa'",
                            [$clienteId]
                        );

                        // Insertar nueva membresía
                        $this->membresiaModel->query(
                            "INSERT INTO cliente_membresias 
                             (cliente_id, membresia_id, fecha_inicio, fecha_fin, estado) 
                             VALUES (?, ?, ?, ?, 'activa')",
                            [
                                $clienteId,
                                $membresiaId,
                                $inicio->format('Y-m-d'),
                                $fin->format('Y-m-d')
                            ]
                        );

                        // Registrar el pago
                        $pagoModel = $this->model('Pago');
                        $pagoModel->insert([
                            'cliente_id' => $clienteId,
                            'concepto' => 'Membresía: ' . $membresia['nombre'],
                            'monto' => $membresia['precio'],
                            'metodo_pago' => $metodoPago,
                            'fecha_pago' => date('Y-m-d H:i:s'),
                            'usuario_id' => $_SESSION['user_id']
                        ]);

                        $_SESSION['success'] = 'Membresía asignada correctamente';
                        $this->redirect('clientes/ver/' . $clienteId);
                    } catch (Exception $e) {
                        $errors[] = 'Error al asignar la membresía: ' . $e->getMessage();
                    }
                } else {
                    $errors[] = 'Membresía no encontrada';
                }
            }

            $data['errors'] = $errors;
            $data['old'] = $_POST;
        }

        $this->view('membresias/asignar', $data);
    }
}
