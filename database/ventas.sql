-- ===================================
-- Tablas para Módulo de Ventas
-- ===================================

-- Tabla de ventas
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    folio VARCHAR(20) NOT NULL UNIQUE,
    cliente_nombre VARCHAR(100) DEFAULT NULL,
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('completada', 'anulada') NOT NULL DEFAULT 'completada',
    usuario_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE RESTRICT,
    INDEX idx_folio (folio),
    INDEX idx_fecha (fecha),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de detalle de ventas
CREATE TABLE IF NOT EXISTS ventas_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT,
    INDEX idx_venta (venta_id),
    INDEX idx_producto (producto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
