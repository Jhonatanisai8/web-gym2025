<?php

/**
 * Modelo base
 * Todos los modelos heredan de esta clase
 */
class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtiene todos los registros
     */
    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    /**
     * Obtiene un registro por ID
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Inserta un nuevo registro
     */
    public function insert($data)
    {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }

    /**
     * Actualiza un registro
     */
    public function update($id, $data)
    {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= "{$key} = :{$key}, ";
        }
        $fields = rtrim($fields, ', ');

        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }

    /**
     * Elimina un registro
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Cuenta registros
     */
    public function count($where = '')
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Ejecuta una consulta personalizada
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Obtiene el último ID insertado
     */
    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }
}
