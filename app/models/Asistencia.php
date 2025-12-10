<?php

/**
 * Modelo de Asistencia
 */
class Asistencia extends Model
{

    protected $table = 'asistencias';

    /**
     * Registra una asistencia
     */
    public function registrar($clienteId, $usuarioId)
    {
        // Verificar que el cliente tenga membresía activa
        $stmt = $this->db->prepare("
            SELECT cm.* 
            FROM cliente_membresias cm
            WHERE cm.cliente_id = ? 
            AND cm.estado = 'activa'
            AND cm.fecha_fin >= CURRENT_DATE()
            LIMIT 1
        ");
        $stmt->execute([$clienteId]);
        $membresia = $stmt->fetch();

        if (!$membresia) {
            return ['success' => false, 'message' => 'El cliente no tiene membresía activa o está vencida'];
        }

        // Registrar asistencia
        $stmt = $this->db->prepare("
            INSERT INTO asistencias (cliente_id, usuario_id, fecha_hora)
            VALUES (?, ?, NOW())
        ");

        if ($stmt->execute([$clienteId, $usuarioId])) {
            return ['success' => true, 'message' => 'Asistencia registrada correctamente'];
        }

        return ['success' => false, 'message' => 'Error al registrar asistencia'];
    }

    /**
     * Obtiene asistencias por fecha
     */
    public function getPorFecha($fecha)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, 
                   c.nombre, 
                   c.apellido, 
                   c.dni,
                   u.nombre as registrado_por
            FROM asistencias a
            INNER JOIN clientes c ON a.cliente_id = c.id
            INNER JOIN usuarios u ON a.usuario_id = u.id
            WHERE DATE(a.fecha_hora) = ?
            ORDER BY a.fecha_hora DESC
        ");
        $stmt->execute([$fecha]);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene estadísticas de asistencias
     */
    public function getEstadisticas($fechaInicio, $fechaFin)
    {
        $stmt = $this->db->prepare("
            SELECT DATE(fecha_hora) as fecha, COUNT(*) as total
            FROM asistencias
            WHERE DATE(fecha_hora) BETWEEN ? AND ?
            GROUP BY DATE(fecha_hora)
            ORDER BY fecha ASC
        ");
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll();
    }
}
