<?php

class ProductosController extends Controller
{
    private $productoModel;
    private $categoriaModel;

    public function __construct()
    {
        $this->requireAdmin();
        $this->productoModel = $this->model('Producto');
        $this->categoriaModel = $this->model('Categoria');
    }

    public function index()
    {
        // Configuración de paginación
        $productosPorPagina = 10;
        $paginaActual = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($paginaActual - 1) * $productosPorPagina;

        // Obtener productos paginados
        $productos = $this->productoModel->getAllWithCategoriaPaginated($productosPorPagina, $offset);

        // Calcular total de páginas
        $totalProductos = $this->productoModel->countAll();
        $totalPaginas = ceil($totalProductos / $productosPorPagina);

        $data = [
            'title' => 'Productos',
            'productos' => $productos,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $totalPaginas,
            'totalProductos' => $totalProductos
        ];
        $this->view('productos/index', $data);
    }

    public function crear()
    {
        $categorias = $this->categoriaModel->getAll();
        $data = [
            'title' => 'Nuevo Producto',
            'categorias' => $categorias,
            'errors' => [],
            'old' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $this->getPost('nombre');
            $precio = $this->getPost('precio');
            $categoriaId = (int)$this->getPost('categoria_id');
            $stock = (int)$this->getPost('stock');
            $stockMinimo = (int)$this->getPost('stock_minimo');
            $errors = [];
            if (!$nombre) $errors[] = 'El nombre es obligatorio';
            if ($precio === '' || !is_numeric($precio)) $errors[] = 'El precio es inválido';
            if ($categoriaId <= 0) $errors[] = 'Seleccione una categoría';

            $dataToInsert = [
                'nombre' => $nombre,
                'descripcion' => $this->getPost('descripcion'),
                'categoria_id' => $categoriaId,
                'precio' => $precio,
                'stock' => $stock,
                'stock_minimo' => $stockMinimo,
                'estado' => 'activo'
            ];

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $img = $this->uploadImagen($_FILES['imagen']);
                if ($img) $dataToInsert['imagen'] = $img;
            }

            if (empty($errors)) {
                if ($this->productoModel->insert($dataToInsert)) {
                    $_SESSION['success'] = 'Producto creado';
                    $this->redirect('productos');
                } else {
                    $errors[] = 'Error al crear el producto';
                }
            }

            $data['errors'] = $errors;
            $data['old'] = $_POST;
        }

        $this->view('productos/crear', $data);
    }

    public function editar($id)
    {
        $producto = $this->productoModel->getById($id);
        if (!$producto) {
            $_SESSION['error'] = 'Producto no encontrado';
            $this->redirect('productos');
        }

        $categorias = $this->categoriaModel->getAll();
        $data = [
            'title' => 'Editar Producto',
            'producto' => $producto,
            'categorias' => $categorias,
            'errors' => [],
            'old' => $producto
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $this->getPost('nombre');
            $precio = $this->getPost('precio');
            $categoriaId = (int)$this->getPost('categoria_id');
            $stock = (int)$this->getPost('stock');
            $stockMinimo = (int)$this->getPost('stock_minimo');
            $errors = [];
            if (!$nombre) $errors[] = 'El nombre es obligatorio';
            if ($precio === '' || !is_numeric($precio)) $errors[] = 'El precio es inválido';
            if ($categoriaId <= 0) $errors[] = 'Seleccione una categoría';

            $dataToUpdate = [
                'nombre' => $nombre,
                'descripcion' => $this->getPost('descripcion'),
                'categoria_id' => $categoriaId,
                'precio' => $precio,
                'stock' => $stock,
                'stock_minimo' => $stockMinimo
            ];

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $img = $this->uploadImagen($_FILES['imagen']);
                if ($img) $dataToUpdate['imagen'] = $img;
            }

            if (empty($errors)) {
                if ($this->productoModel->update($id, $dataToUpdate)) {
                    $_SESSION['success'] = 'Producto actualizado';
                    $this->redirect('productos');
                } else {
                    $errors[] = 'Error al actualizar';
                }
            }

            $data['errors'] = $errors;
            $data['old'] = $_POST;
        }

        $this->view('productos/editar', $data);
    }

    public function cambiarEstado($id)
    {
        $p = $this->productoModel->getById($id);
        if ($p) {
            $nuevo = $p['estado'] === 'activo' ? 'inactivo' : 'activo';
            $this->productoModel->update($id, ['estado' => $nuevo]);
            $_SESSION['success'] = 'Estado actualizado';
        }
        $this->redirect('productos');
    }

    private function uploadImagen($file)
    {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) return false;
        $filename = uniqid() . '_' . $file['name'];
        $destination = UPLOAD_PATH . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) return $filename;
        return false;
    }
}
