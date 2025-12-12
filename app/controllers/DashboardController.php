<?php

/**
 * Controlador del Dashboard
 */
class DashboardController extends Controller
{

    public function __construct()
    {
        $this->requireAuth();
    }

    /**
     * Página principal del dashboard
     */
    public function index()
    {
        // Obtener estadísticas
        $db = Database::getInstance()->getConnection();

        // Total de clientes activos
        $stmt = $db->query("SELECT COUNT(*) as total FROM clientes WHERE estado = 'activo'");
        $clientesActivos = $stmt->fetch()['total'];

        // Ingresos del mes actual (pagos de membresías + ventas de productos)
        $stmt = $db->query("
            SELECT 
                (
                    SELECT COALESCE(SUM(monto), 0) 
                    FROM pagos 
                    WHERE MONTH(fecha_pago) = MONTH(CURRENT_DATE()) 
                    AND YEAR(fecha_pago) = YEAR(CURRENT_DATE())
                ) + 
                (
                    SELECT COALESCE(SUM(total), 0) 
                    FROM ventas 
                    WHERE MONTH(fecha_venta) = MONTH(CURRENT_DATE()) 
                    AND YEAR(fecha_venta) = YEAR(CURRENT_DATE())
                ) as total
        ");
        $ingresosMes = $stmt->fetch()['total'];

        // Membresías por vencer (próximos 7 días)
        $stmt = $db->query("
            SELECT c.nombre, c.apellido, cm.fecha_fin, m.nombre as membresia
            FROM cliente_membresias cm
            INNER JOIN clientes c ON cm.cliente_id = c.id
            INNER JOIN membresias m ON cm.membresia_id = m.id
            WHERE cm.estado = 'activa' 
            AND cm.fecha_fin BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)
            ORDER BY cm.fecha_fin ASC
            LIMIT 10
        ");
        $membresiasPorVencer = $stmt->fetchAll();

        // Asistencias del día
        $stmt = $db->query("
            SELECT COUNT(*) as total 
            FROM asistencias 
            WHERE DATE(fecha_hora) = CURRENT_DATE()
        ");
        $asistenciasHoy = $stmt->fetch()['total'];

        // Total de productos con stock bajo
        $stmt = $db->query("
            SELECT COUNT(*) as total 
            FROM productos 
            WHERE stock <= stock_minimo AND estado = 'activo'
        ");
        $productosStockBajo = $stmt->fetch()['total'];

        $data = [
            'title' => 'Dashboard',
            'clientesActivos' => $clientesActivos,
            'ingresosMes' => $ingresosMes,
            'membresiasPorVencer' => $membresiasPorVencer,
            'asistenciasHoy' => $asistenciasHoy,
            'productosStockBajo' => $productosStockBajo
        ];

        $this->view('dashboard/index', $data);
    }
}
