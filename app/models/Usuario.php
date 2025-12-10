<?php

/**
 * Modelo de Usuario
 */
class Usuario extends Model
{

    protected $table = 'usuarios';

    /**
     * Busca un usuario por email
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre as rol_nombre 
            FROM usuarios u 
            INNER JOIN roles r ON u.rol_id = r.id 
            WHERE u.email = ?
        ");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Verifica las credenciales del usuario
     */
    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['estado'] === 'activo') {
                return $user;
            }
        }

        return false;
    }

    /**
     * Crea un nuevo usuario
     */
    public function create($data)
    {
        // Hash de la contraseña
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        return $this->insert($data);
    }

    /**
     * Actualiza la contraseña de un usuario
     */
    public function updatePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    /**
     * Obtiene todos los usuarios con su rol
     */
    public function getAllWithRole()
    {
        $stmt = $this->db->query("
            SELECT u.*, r.nombre as rol_nombre 
            FROM usuarios u 
            INNER JOIN roles r ON u.rol_id = r.id
            ORDER BY u.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Cambia el estado de un usuario
     */
    public function changeStatus($id, $estado)
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }

    /**
     * Verifica si un email ya existe
     */
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE email = ?";
        $params = [$email];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();

        return $result['total'] > 0;
    }
}
