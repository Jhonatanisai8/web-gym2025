<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - Sistema Gimnasio</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>

<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 6h4v12H6zM14 6h4v12h-4zM2 9h2M20 9h2M2 15h2M20 15h2" />
                    </svg>
                </div>
                <h2>Gimnasio</h2>
            </div>

            <nav class="sidebar-nav">
                <a href="<?= BASE_URL ?>dashboard" class="nav-item">
                    <img src="<?= BASE_URL ?>public/img/Dashboard.png" alt="Dashboard" class="nav-icon">
                    Dashboard
                </a>

                <a href="<?= BASE_URL ?>clientes" class="nav-item">
                    <img src="<?= BASE_URL ?>public/img/Clientes.png" alt="Clientes" class="nav-icon">
                    Clientes
                </a>

                <a href="<?= BASE_URL ?>membresias" class="nav-item">
                    <img src="<?= BASE_URL ?>public/img/Membresias.png" alt="Membresías" class="nav-icon">
                    Membresías
                </a>

                <a href="<?= BASE_URL ?>asistencias" class="nav-item">
                    <img src="<?= BASE_URL ?>public/img/Asistencias.png" alt="Asistencias" class="nav-icon">
                    Asistencias
                </a>

                <a href="<?= BASE_URL ?>pagos" class="nav-item">
                    <img src="<?= BASE_URL ?>public/img/Pagos.png" alt="Pagos" class="nav-icon">
                    Pagos
                </a>

                <a href="<?= BASE_URL ?>productos" class="nav-item">
                    <img src="<?= BASE_URL ?>public/img/Productos.png" alt="Productos" class="nav-icon">
                    Productos
                </a>

                <?php if ($_SESSION['rol_nombre'] === 'Administrador'): ?>
                    <a href="<?= BASE_URL ?>usuarios" class="nav-item">
                        <img src="<?= BASE_URL ?>public/img/Usuarios.png" alt="Usuarios" class="nav-icon">
                        Usuarios
                    </a>
                <?php endif; ?>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <header class="topbar">
                <div class="topbar-left">
                    <h1><?= $title ?? 'Dashboard' ?></h1>
                </div>
                <div class="topbar-right">
                    <div class="user-menu">
                        <div class="user-info">
                            <span class="user-name"><?= $_SESSION['user_nombre'] ?></span>
                            <span class="user-role"><?= $_SESSION['rol_nombre'] ?></span>
                        </div>
                        <div class="user-avatar">
                            <?php if (!empty($_SESSION['user_foto'])): ?>
                                <img src="<?= BASE_URL ?>public/uploads/<?= $_SESSION['user_foto'] ?>" alt="Avatar">
                            <?php else: ?>
                                <div class="avatar-placeholder">
                                    <?= strtoupper(substr($_SESSION['user_nombre'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <a href="<?= BASE_URL ?>auth/logout" class="btn-logout" title="Cerrar Sesión">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="page-content">