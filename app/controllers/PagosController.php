<?php

class PagosController extends Controller
{
    private $pagoModel;
    private $membresiaModel;
    private $clienteModel;

    public function __construct()
    {
        $this->requireAuth();
        $this->pagoModel = $this->model('Pago');
        $this->membresiaModel = $this->model('Membresia');
        $this->clienteModel = $this->model('Cliente');
    }

    public function index()
    {
        $pagos = $this->pagoModel->getAllWithRel();
        $data = [
            'title' => 'Pagos',
            'pagos' => $pagos
        ];
        $this->view('pagos/index', $data);
    }

    public function crear()
    {
        $clientes = $this->clienteModel->getAll();
        $membresias = $this->membresiaModel->getActivas();
        $data = [
            'title' => 'Registrar Pago',
            'clientes' => $clientes,
            'membresias' => $membresias,
            'errors' => [],
            'old' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clienteId = (int)$this->getPost('cliente_id');
            $membresiaId = (int)$this->getPost('membresia_id');
            $metodo = $this->getPost('metodo_pago');
            $obs = $this->getPost('observaciones');
            $errors = [];
            if ($clienteId <= 0) $errors[] = 'Seleccione un cliente';
            if ($membresiaId <= 0) $errors[] = 'Seleccione una membresía';
            if (!$metodo) $errors[] = 'Seleccione el método de pago';

            if (empty($errors)) {
                $m = $this->membresiaModel->getById($membresiaId);
                if (!$m) $errors[] = 'Membresía no válida';
                if (empty($errors)) {
                    $this->membresiaModel->asignarCliente($clienteId, $membresiaId);
                    $activa = $this->clienteModel->getMembresiaActiva($clienteId);
                    $cmId = $activa ? $activa['id'] : null;
                    $ok = $this->pagoModel->insert([
                        'cliente_id' => $clienteId,
                        'cliente_membresia_id' => $cmId,
                        'monto' => $m['precio'],
                        'metodo_pago' => $metodo,
                        'observaciones' => $obs,
                        'usuario_id' => $_SESSION['user_id']
                    ]);
                    if ($ok) {
                        $_SESSION['success'] = 'Pago registrado';
                        $this->redirect('pagos');
                    } else {
                        $errors[] = 'Error al registrar el pago';
                    }
                }
            }

            $data['errors'] = $errors;
            $data['old'] = $_POST;
        }

        $this->view('pagos/crear', $data);
    }
}

