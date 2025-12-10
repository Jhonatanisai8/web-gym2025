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
                <a href="<?= BASE_URL ?>dashboard" class="nav-item active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                    Dashboard
                </a>

                <a href="<?= BASE_URL ?>clientes" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    Clientes
                </a>

                <a href="<?= BASE_URL ?>membresias" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                        <line x1="1" y1="10" x2="23" y2="10" />
                    </svg>
                    Membresías
                </a>

                <a href="<?= BASE_URL ?>asistencias" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    Asistencias
                </a>

                <a href="<?= BASE_URL ?>pagos" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                    Pagos
                </a>

                <a href="<?= BASE_URL ?>productos" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    Productos
                </a>

                <?php if ($_SESSION['rol_nombre'] === 'Administrador'): ?>
                    <a href="<?= BASE_URL ?>usuarios" class="nav-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="8.5" cy="7" r="4" />
                            <line x1="20" y1="8" x2="20" y2="14" />
                            <line x1="23" y1="11" x2="17" y2="11" />
                        </svg>
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