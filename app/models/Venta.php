<?php

class Venta extends Model
{
    protected $table = 'ventas';

    public function getAllWithPagination($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nombre as usuario_nombre, 
                   CONCAT(c.nombre, ' ', c.apellido) as cliente_nombre
            FROM ventas v 
            INNER JOIN usuarios u ON v.usuario_id = u.id 
            LEFT JOIN clientes c ON v.cliente_id = c.id
            ORDER BY v.fecha_venta DESC 
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        return $stmt->fetch()['total'];
    }

    public function getWithDetails($id)
    {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nombre as usuario_nombre,
                   CONCAT(c.nombre, ' ', c.apellido) as cliente_nombre,
                   c.dni as cliente_dni
            FROM ventas v 
            INNER JOIN usuarios u ON v.usuario_id = u.id 
            LEFT JOIN clientes c ON v.cliente_id = c.id
            WHERE v.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function createWithDetails($ventaData, $detalles)
    {
        try {
            $this->db->beginTransaction();

            // Insertar venta
            $ventaId = $this->insert($ventaData);

            if (!$ventaId) {
                throw new Exception("Error al crear la venta");
            }

            // Insertar detalles y actualizar stock
            $productoModel = new Producto();
            foreach ($detalles as $detalle) {
                $detalle['venta_id'] = $ventaId;

                // Insertar detalle
                $stmt = $this->db->prepare("
                    INSERT INTO venta_detalles (venta_id, producto_id, cantidad, precio_unitario, subtotal)
                    VALUES (:venta_id, :producto_id, :cantidad, :precio_unitario, :subtotal)
                ");
                $stmt->execute($detalle);

                // Actualizar stock del producto
                $producto = $productoModel->getById($detalle['producto_id']);
                if ($producto) {
                    $nuevoStock = $producto['stock'] - $detalle['cantidad'];
                    $productoModel->update($detalle['producto_id'], ['stock' => $nuevoStock]);
                }
            }

            $this->db->commit();
            return $ventaId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en createWithDetails: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id)
    {
        try {
            $this->db->beginTransaction();

            // Obtener detalles de la venta
            $stmt = $this->db->prepare("SELECT * FROM venta_detalles WHERE venta_id = :venta_id");
            $stmt->execute(['venta_id' => $id]);
            $detalles = $stmt->fetchAll();

            // Restaurar stock
            $productoModel = new Producto();
            foreach ($detalles as $detalle) {
                $producto = $productoModel->getById($detalle['producto_id']);
                if ($producto) {
                    $nuevoStock = $producto['stock'] + $detalle['cantidad'];
                    $productoModel->update($detalle['producto_id'], ['stock' => $nuevoStock]);
                }
            }

            // Eliminar venta (los detalles se eliminan automáticamente por CASCADE)
            $this->delete($id);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error al eliminar venta: " . $e->getMessage());
            return false;
        }
    }
}
