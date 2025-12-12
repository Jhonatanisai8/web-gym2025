<?php

/**
 * Controlador de Asistencias
 */
class AsistenciasController extends Controller
{

    private $asistenciaModel;
    private $clienteModel;

    public function __construct()
    {
        $this->requireAuth();
        $this->asistenciaModel = $this->model('Asistencia');
        $this->clienteModel = $this->model('Cliente');
    }

    /**
     * Página principal de asistencias
     */
    public function index()
    {
        $fecha = $this->getGet('fecha', date('Y-m-d'));
        $page = (int)$this->getGet('page', 1);

        if ($page < 1) {
            $page = 1;
        }

        $perPage = 20;
        $total = (int)$this->asistenciaModel->countPorFecha($fecha);
        $totalPages = max(1, (int)ceil($total / $perPage));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;
        $asistencias = $this->asistenciaModel->getPorFechaPaginado($fecha, $perPage, $offset);

        $data = [
            'title' => 'Control de Asistencias',
            'asistencias' => $asistencias,
            'fecha' => $fecha,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages
        ];

        $this->view('asistencias/index', $data);
    }

    /**
     * Registrar asistencia
     */
    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni = $this->getPost('dni');

            // Buscar cliente por DNI
            $cliente = $this->clienteModel->findByDni($dni);

            if (!$cliente) {
                $this->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }

            if ($cliente['estado'] !== 'activo') {
                $this->json([
                    'success' => false,
                    'message' => 'El cliente está inactivo'
                ], 400);
            }

            // Registrar asistencia
            $result = $this->asistenciaModel->registrar(
                $cliente['id'],
                $_SESSION['user_id']
            );

            if ($result['success']) {
                $this->json([
                    'success' => true,
                    'message' => $result['message'],
                    'cliente' => $cliente['nombre'] . ' ' . $cliente['apellido']
                ]);
            } else {
                $this->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }
        }

        $data = ['title' => 'Registrar Asistencia'];
        $this->view('asistencias/registrar', $data);
    }
}
