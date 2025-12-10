<?php

class Producto extends Model
{
    protected $table = 'productos';

    public function getAllWithCategoria()
    {
        $stmt = $this->db->query("SELECT p.*, c.nombre as categoria_nombre FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id ORDER BY p.created_at DESC");
        return $stmt->fetchAll();
    }
}

