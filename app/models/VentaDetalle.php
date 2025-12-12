<?php

class VentaDetalle extends Model
{
    protected $table = 'venta_detalles';

    public function getByVentaId($ventaId)
    {
        $stmt = $this->db->prepare("
            SELECT vd.*, p.nombre as producto_nombre, p.imagen as producto_imagen
            FROM venta_detalles vd
            INNER JOIN productos p ON vd.producto_id = p.id
            WHERE vd.venta_id = :venta_id
            ORDER BY vd.id
        ");
        $stmt->execute(['venta_id' => $ventaId]);
        return $stmt->fetchAll();
    }
}
