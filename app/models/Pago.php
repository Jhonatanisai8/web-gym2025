<?php

class Pago extends Model
{
    protected $table = 'pagos';

    public function getAllWithRel()
    {
        $sql = "
            SELECT p.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido,
                   m.nombre as membresia_nombre
            FROM pagos p
            INNER JOIN clientes c ON p.cliente_id = c.id
            LEFT JOIN cliente_membresias cm ON p.cliente_membresia_id = cm.id
            LEFT JOIN membresias m ON cm.membresia_id = m.id
            ORDER BY p.fecha_pago DESC
        ";
        return $this->query($sql)->fetchAll();
    }
}

