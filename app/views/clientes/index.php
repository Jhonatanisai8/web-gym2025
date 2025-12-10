<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <h1><?= $title ?></h1>
        <p>Administra los clientes del gimnasio</p>
    </div>
    <div class="page-header-right">
        <a href="<?= BASE_URL ?>clientes/crear" class="btn btn-primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Nuevo Cliente
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success'];
        unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Lista de Clientes</h2>
        <div class="search-box">
            <input type="text" id="searchClientes" placeholder="Buscar por nombre, apellido o DNI..." class="form-control">
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>DNI</th>
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Membresía</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="clientesTableBody">
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay clientes registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($cliente['foto'])): ?>
                                        <img src="<?= BASE_URL ?>public/uploads/<?= $cliente['foto'] ?>"
                                            alt="Foto" class="table-avatar">
                                    <?php else: ?>
                                        <div class="table-avatar-placeholder">
                                            <?= strtoupper(substr($cliente['nombre'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($cliente['dni']) ?></td>
                                <td><?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?></td>
                                <td><?= htmlspecialchars($cliente['telefono'] ?? '-') ?></td>
                                <td>
                                    <?php if (!empty($cliente['membresia_nombre'])): ?>
                                        <span class="badge badge-<?= $cliente['membresia_estado'] === 'activa' ? 'success' : 'danger' ?>">
                                            <?= htmlspecialchars($cliente['membresia_nombre']) ?>
                                        </span>
                                        <?php if ($cliente['membresia_estado'] === 'activa'): ?>
                                            <small class="text-muted">
                                                Vence: <?= date('d/m/Y', strtotime($cliente['fecha_fin'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Sin membresía</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $cliente['estado'] === 'activo' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($cliente['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>clientes/ver/<?= $cliente['id'] ?>"
                                            class="btn btn-sm btn-secondary" title="Ver detalles">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                        <a href="<?= BASE_URL ?>clientes/editar/<?= $cliente['id'] ?>"
                                            class="btn btn-sm btn-primary" title="Editar">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                        <a href="<?= BASE_URL ?>clientes/cambiarEstado/<?= $cliente['id'] ?>"
                                            class="btn btn-sm btn-<?= $cliente['estado'] === 'activo' ? 'danger' : 'success' ?>"
                                            data-confirm="¿Está seguro de cambiar el estado?"
                                            title="<?= $cliente['estado'] === 'activo' ? 'Desactivar' : 'Activar' ?>">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10" />
                                                <line x1="15" y1="9" x2="9" y2="15" />
                                                <line x1="9" y1="9" x2="15" y2="15" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-header-left h1 {
        margin-bottom: 5px;
    }

    .page-header-left p {
        color: var(--text-secondary);
        font-size: 14px;
    }

    .search-box {
        min-width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
    }

    .table-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .table-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .btn-group {
        display: flex;
        gap: 5px;
    }

    .text-center {
        text-align: center;
    }

    .text-muted {
        color: var(--text-secondary);
        font-size: 12px;
        display: block;
    }
</style>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>