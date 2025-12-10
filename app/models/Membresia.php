<?php

/**
 * Modelo de Membresía
 */
class Membresia extends Model
{

    protected $table = 'membresias';

    /**
     * Obtiene todas las membresías activas
     */
    public function getActivas()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE estado = 'activo' ORDER BY precio ASC");
        return $stmt->fetchAll();
    }

    /**
     * Asigna una membresía a un cliente
     */
    public function asignarCliente($clienteId, $membresiaId, $fechaInicio = null)
    {
        // Obtener información de la membresía
        $membresia = $this->getById($membresiaId);
        if (!$membresia) {
            return false;
        }

        // Calcular fechas
        $fechaInicio = $fechaInicio ?? date('Y-m-d');
        $fechaFin = date('Y-m-d', strtotime($fechaInicio . ' + ' . $membresia['duracion_dias'] . ' days'));

        // Desactivar membresías anteriores del cliente
        $this->db->prepare("
            UPDATE cliente_membresias 
            SET estado = 'cancelada' 
            WHERE cliente_id = ? AND estado = 'activa'
        ")->execute([$clienteId]);

        // Insertar nueva membresía
        $stmt = $this->db->prepare("
            INSERT INTO cliente_membresias (cliente_id, membresia_id, fecha_inicio, fecha_fin, estado)
            VALUES (?, ?, ?, ?, 'activa')
        ");

        return $stmt->execute([$clienteId, $membresiaId, $fechaInicio, $fechaFin]);
    }

    /**
     * Obtiene el historial de membresías de un cliente
     */
    public function getHistorialCliente($clienteId)
    {
        $stmt = $this->db->prepare("
            SELECT cm.*, m.nombre, m.precio
            FROM cliente_membresias cm
            INNER JOIN membresias m ON cm.membresia_id = m.id
            WHERE cm.cliente_id = ?
            ORDER BY cm.created_at DESC
        ");
        $stmt->execute([$clienteId]);
        return $stmt->fetchAll();
    }
}
