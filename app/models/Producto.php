<?php

class Producto extends Model
{
    protected $table = 'productos';

    public function getAllWithCategoria()
    {
        $stmt = $this->db->query("SELECT p.*, c.nombre as categoria_nombre FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id ORDER BY p.created_at DESC");
        return $stmt->fetchAll();
    }

    public function getAllWithCategoriaPaginated($limit, $offset)
    {
        $stmt = $this->db->prepare("SELECT p.*, c.nombre as categoria_nombre FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset");
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
}
