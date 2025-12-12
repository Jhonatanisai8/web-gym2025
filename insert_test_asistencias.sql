-- Script para insertar datos de prueba en asistencias
-- Asegúrate de tener clientes y usuarios en la base de datos primero

USE gimnasio_db;

-- Insertar asistencias de prueba para hoy
-- Nota: Ajusta los IDs de cliente_id y usuario_id según los datos de tu base de datos

-- Asistencias de hoy (fecha actual)
INSERT INTO asistencias (cliente_id, usuario_id, fecha_hora) VALUES
(1, 1, NOW()),
(2, 1, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(3, 1, DATE_SUB(NOW(), INTERVAL 4 HOUR)),
(4, 1, DATE_SUB(NOW(), INTERVAL 6 HOUR));

-- Asistencias de ayer
INSERT INTO asistencias (cliente_id, usuario_id, fecha_hora) VALUES
(1, 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(5, 1, DATE_SUB(NOW(), INTERVAL 1 DAY));

-- Asistencias de hace 2 días
INSERT INTO asistencias (cliente_id, usuario_id, fecha_hora) VALUES
(1, 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(3, 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(4, 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(5, 1, DATE_SUB(NOW(), INTERVAL 2 DAY));

-- Verificar las asistencias insertadas
SELECT 
    a.id,
    a.fecha_hora,
    c.nombre,
    c.apellido,
    c.dni,
    u.nombre as registrado_por
FROM asistencias a
INNER JOIN clientes c ON a.cliente_id = c.id
INNER JOIN usuarios u ON a.usuario_id = u.id
ORDER BY a.fecha_hora DESC
LIMIT 20;
