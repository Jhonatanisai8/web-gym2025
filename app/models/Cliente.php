<?php

/**
 * Modelo de Cliente
 */
class Cliente extends Model
{

    protected $table = 'clientes';

    /**
     * Obtiene todos los clientes con información de membresía activa
     */
    public function getAllWithMembership()
    {
        $sql = "
            SELECT 
                c.*,
                cm.id as membresia_activa_id,
                cm.fecha_inicio,
                cm.fecha_fin,
                cm.estado as membresia_estado,
                m.nombre as membresia_nombre
            FROM clientes c
            LEFT JOIN cliente_membresias cm ON c.id = cm.cliente_id AND cm.estado = 'activa'
            LEFT JOIN membresias m ON cm.membresia_id = m.id
            ORDER BY c.created_at DESC
        ";

        return $this->query($sql)->fetchAll();
    }

    /**
     * Busca cliente por DNI
     */
    public function findByDni($dni)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE dni = ?");
        $stmt->execute([$dni]);
        return $stmt->fetch();
    }

    /**
     * Busca clientes (por nombre, apellido o DNI)
     */
    public function search($term)
    {
        $stmt = $this->db->prepare("
            SELECT c.*,
                   cm.fecha_fin,
                   cm.estado as membresia_estado,
                   m.nombre as membresia_nombre
            FROM clientes c
            LEFT JOIN cliente_membresias cm ON c.id = cm.cliente_id AND cm.estado = 'activa'
            LEFT JOIN membresias m ON cm.membresia_id = m.id
            WHERE c.nombre LIKE ? 
               OR c.apellido LIKE ? 
               OR c.dni LIKE ?
            ORDER BY c.nombre ASC
        ");

        $searchTerm = "%{$term}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    /**
     * Verifica si un DNI ya existe
     */
    public function dniExists($dni, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE dni = ?";
        $params = [$dni];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();

        return $result['total'] > 0;
    }

    /**
     * Obtiene el historial de asistencias de un cliente
     */
    public function getAsistencias($clienteId, $limit = 20)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, u.nombre as registrado_por
            FROM asistencias a
            INNER JOIN usuarios u ON a.usuario_id = u.id
            WHERE a.cliente_id = ?
            ORDER BY a.fecha_hora DESC
            LIMIT ?
        ");
        $stmt->execute([$clienteId, $limit]);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene la membresía activa de un cliente
     */
    public function getMembresiaActiva($clienteId)
    {
        $stmt = $this->db->prepare("
            SELECT cm.*, m.nombre as membresia_nombre, m.precio
            FROM cliente_membresias cm
            INNER JOIN membresias m ON cm.membresia_id = m.id
            WHERE cm.cliente_id = ? AND cm.estado = 'activa'
            ORDER BY cm.fecha_fin DESC
            LIMIT 1
        ");
        $stmt->execute([$clienteId]);
        return $stmt->fetch();
    }
}
