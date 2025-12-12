<?php

class VentasController extends Controller
{
    private $ventaModel;
    private $ventaDetalleModel;
    private $productoModel;
    private $clienteModel;

    public function __construct()
    {
        $this->requireAdmin();
        $this->ventaModel = $this->model('Venta');
        $this->ventaDetalleModel = $this->model('VentaDetalle');
        $this->productoModel = $this->model('Producto');
        $this->clienteModel = $this->model('Cliente');
    }

    public function index()
    {
        // Configuración de paginación
        $ventasPorPagina = 10;
        $paginaActual = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($paginaActual - 1) * $ventasPorPagina;

        // Obtener ventas paginadas
        $ventas = $this->ventaModel->getAllWithPagination($ventasPorPagina, $offset);

        // Calcular total de páginas
        $totalVentas = $this->ventaModel->countAll();
        $totalPaginas = ceil($totalVentas / $ventasPorPagina);

        $data = [
            'title' => 'Ventas',
            'ventas' => $ventas,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $totalPaginas,
            'totalVentas' => $totalVentas
        ];
        $this->view('ventas/index', $data);
    }

    public function crear()
    {
        $productos = $this->productoModel->getAll();
        // Filtrar solo productos activos con stock
        $productos = array_filter($productos, function ($p) {
            return $p['estado'] === 'activo' && $p['stock'] > 0;
        });

        $clientes = $this->clienteModel->getAll();
        // Filtrar solo clientes activos
        $clientes = array_filter($clientes, function ($c) {
            return $c['estado'] === 'activo';
        });

        $data = [
            'title' => 'Nueva Venta',
            'productos' => $productos,
            'clientes' => $clientes,
            'errors' => [],
            'old' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clienteId = $this->getPost('cliente_id');
            $metodoPago = $this->getPost('metodo_pago');
            $productosVenta = json_decode($this->getPost('productos_json'), true);
            $total = (float)$this->getPost('total');

            $errors = [];

            // Validaciones
            if (empty($productosVenta) || !is_array($productosVenta)) {
                $errors[] = 'Debe agregar al menos un producto';
            }

            if ($total <= 0) {
                $errors[] = 'El total debe ser mayor a 0';
            }

            if (empty($metodoPago)) {
                $errors[] = 'Debe seleccionar un método de pago';
            }

            // Validar stock disponible
            foreach ($productosVenta as $item) {
                $producto = $this->productoModel->getById($item['producto_id']);
                if (!$producto) {
                    $errors[] = "Producto no encontrado";
                    break;
                }
                if ($producto['stock'] < $item['cantidad']) {
                    $errors[] = "Stock insuficiente para {$producto['nombre']}. Disponible: {$producto['stock']}";
                }
            }

            if (empty($errors)) {
                // Preparar datos de la venta
                $ventaData = [
                    'cliente_id' => $clienteId ?: null,
                    'total' => $total,
                    'metodo_pago' => $metodoPago,
                    'usuario_id' => $_SESSION['user_id']
                ];

                // Preparar detalles
                $detalles = [];
                foreach ($productosVenta as $item) {
                    $detalles[] = [
                        'producto_id' => $item['producto_id'],
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                        'subtotal' => $item['subtotal']
                    ];
                }

                // Crear venta con detalles
                $ventaId = $this->ventaModel->createWithDetails($ventaData, $detalles);

                if ($ventaId) {
                    $_SESSION['success'] = 'Venta registrada exitosamente';
                    $this->redirect('ventas/ver/' . $ventaId);
                } else {
                    $errors[] = 'Error al registrar la venta';
                }
            }

            $data['errors'] = $errors;
            $data['old'] = $_POST;
        }

        $this->view('ventas/crear', $data);
    }

    public function ver($id)
    {
        $venta = $this->ventaModel->getWithDetails($id);
        if (!$venta) {
            $_SESSION['error'] = 'Venta no encontrada';
            $this->redirect('ventas');
        }

        $detalles = $this->ventaDetalleModel->getByVentaId($id);

        $data = [
            'title' => 'Detalle de Venta',
            'venta' => $venta,
            'detalles' => $detalles
        ];

        $this->view('ventas/ver', $data);
    }

    public function eliminar($id)
    {
        $venta = $this->ventaModel->getById($id);
        if (!$venta) {
            $_SESSION['error'] = 'Venta no encontrada';
            $this->redirect('ventas');
        }

        if ($this->ventaModel->eliminar($id)) {
            $_SESSION['success'] = 'Venta eliminada exitosamente. Stock restaurado.';
        } else {
            $_SESSION['error'] = 'Error al eliminar la venta';
        }

        $this->redirect('ventas');
    }

    public function getProductoInfo($id)
    {
        header('Content-Type: application/json');
        $producto = $this->productoModel->getById($id);

        if ($producto) {
            echo json_encode([
                'success' => true,
                'producto' => $producto
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Producto no encontrado'
            ]);
        }
        exit;
    }
}
