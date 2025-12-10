<?php

/**
 * Configuración de la base de datos
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'gimnasio_db');
define('DB_USER', 'root');
define('DB_PASS', 'admin123');
define('DB_PORT', 3307);
define('DB_CHARSET', 'utf8mb4');

/**
 * Clase Database - Maneja la conexión a la base de datos usando PDO
 */
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    /**
     * Obtiene la instancia única de la base de datos (Singleton)
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtiene la conexión PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Previene la clonación del objeto
     */
    private function __clone() {}

    /**
     * Previene la deserialización del objeto
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}
