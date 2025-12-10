✅ LISTA DE REQUERIMIENTOS DEL SISTEMA — GIMNASIO (PHP + MVC)
1. Descripción General

Desarrollar un sistema web en PHP utilizando el patrón Modelo-Vista-Controlador (MVC), para gestionar un gimnasio que actualmente maneja sus registros de manera manual.
El sistema debe permitir administrar:

Clientes

Membresías

Pagos

Asistencias

Inventario de productos

Usuarios del sistema (admin y usuario normal)

✅ 2. Requerimientos Funcionales
2.1 Gestión de Usuarios del Sistema
Perfiles:

Administrador

Acceso total a todas las funciones.

Gestión completa de usuarios del sistema.

Usuario (empleado del gimnasio)

Acceso limitado.

Sin permisos para eliminar usuarios internos.

Puede registrar clientes, asistencias y pagos.

Funciones:

Crear cuenta de usuario interno (solo admin).

Editar perfil.

Cambiar contraseña.

Activar/desactivar usuarios.

Login seguro con sesión.

Logout.

2.2 Gestión de Clientes

El administrador o usuario puede:

Registrar nuevos clientes.

Ver lista de clientes.

Editar datos.

Adjuntar foto opcional.

Cambiar estado (activo / inactivo).

Buscar clientes por DNI, nombre o membresía.

Ver historial de asistencias del cliente.

2.3 Gestión de Membresías

El sistema debe permitir:

Crear planes de membresías (ejemplo: mensual, trimestral, anual).

Definir duración en días.

Definir precio.

Activar/desactivar membresías.

Asignar membresía a un cliente.

Ver fecha de inicio y fecha de expiración.

Mostrar alertas:

Membresía próxima a vencer.

Membresía vencida.

2.4 Control de Pagos

Registrar pago de membresía.

Generar recibo o comprobante (PDF opcional).

Ver historial de pagos por cliente.

Filtros: por fecha, cliente, método de pago.

Reporte total de ingresos por rango de fechas.

2.5 Control de Asistencias

Registrar asistencia mediante búsqueda por DNI o nombre.

Validar si la membresía está activa.

Impedir registrar asistencias si la membresía expiró.

Ver historial de asistencias por cliente.

Reporte de asistencias por fecha.

2.6 Inventario de productos

El sistema manejará productos como:

Proteínas

Suplementos

Agua

Accesorios deportivos

Funciones:

Registrar producto.

Categorías de productos.

Editar producto.

Ajustar stock.

Registrar ventas de productos.

Reporte de ventas.

✅ 3. Requerimientos No Funcionales
3.1 Arquitectura

PHP (mínimo versión 8.x recomendada).

Arquitectura MVC estricta.

Separación de responsabilidades:

Modelos: conexión a base de datos, consultas.

Controladores: lógica de negocio.

Vistas: HTML + CSS + JS.

Uso de PDO para conexión segura.

3.2 Base de Datos

Motor recomendado: MySQL o MariaDB

Tablas mínimas:

usuarios

roles

clientes

membresias

pagos

asistencias

productos

categorias

ventas

configuraciones (opcional)

3.3 Seguridad

Login con cifrado de contraseñas usando password_hash.

Validación de sesiones.

Filtros de seguridad: evitar SQL Injection.

Control de acceso por roles.

Validación de formularios.

3.4 Interfaz

Panel administrativo responsivo.

Navegación clara.

Dashboard con estadísticas:

Clientes activos

Membresías por expirar

Ingresos del mes

Asistencias del día

✅ 4. Requerimientos del Dashboard

El dashboard deberá mostrar:

Total de clientes activos

Total ingresos del mes

Membresías por vencer (lista de alertas)

Asistencias del día

Gráfica simple (mensual) opcional

✅ 5. Requerimientos del Sistema de Autorización
Admin puede:

Crear/editar/eliminar usuarios

Gestionar todo el sistema

Usuario normal puede:

Registrar asistencias

Registrar clientes

Registrar pagos

Registrar ventas

No puede eliminar usuarios internos

🧠 PROMPT MAESTRO PARA ENTREGAR A LA IA DE VS CODE

Copia y usa este prompt tal cual:

PROMPT PARA AYUDA EN PROGRAMACIÓN (PÉGALE ESTO A LA IA DE VSCODE):

“A partir de ahora te comportarás como un asistente experto en PHP con arquitectura MVC. Quiero que me ayudes a desarrollar un sistema de administración para un gimnasio con los siguientes requerimientos:

– Desarrollado en PHP 8 usando el patrón Modelo–Vista–Controlador.
– Gestión de usuarios (admin y usuario normal) con roles y permisos.
– Gestión de clientes, membresías, asistencias, pagos e inventario.
– Base de datos MySQL.
– Seguridad con password_hash, validación de formularios y sesiones.
– Vistas separadas del controlador; nada de mezclar lógica con HTML.
– Control de acceso según rol.

Quiero que cada vez que te pida algo, me generes únicamente el archivo que corresponda (modelo, vista o controlador) bien estructurado, sin mezclar partes. También quiero que me indiques dónde colocar cada archivo dentro de la arquitectura MVC.”