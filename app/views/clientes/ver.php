<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <a href="<?= BASE_URL ?>clientes" class="btn btn-secondary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12" />
                <polyline points="12 19 5 12 12 5" />
            </svg>
            Volver
        </a>
    </div>
    <div class="page-header-right">
        <a href="<?= BASE_URL ?>clientes/editar/<?= $cliente['id'] ?>" class="btn btn-primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
            Editar
        </a>
    </div>
</div>

<div class="cliente-detail-grid">
    <!-- Información del Cliente -->
    <div class="card">
        <div class="card-header">
            <h2>Información del Cliente</h2>
            <span class="badge badge-<?= $cliente['estado'] === 'activo' ? 'success' : 'danger' ?>">
                <?= ucfirst($cliente['estado']) ?>
            </span>
        </div>
        <div class="card-body">
            <div class="cliente-profile">
                <div class="cliente-avatar-large">
                    <?php if (!empty($cliente['foto'])): ?>
                        <img src="<?= BASE_URL ?>public/uploads/<?= $cliente['foto'] ?>" alt="Foto">
                    <?php else: ?>
                        <div class="avatar-placeholder-large">
                            <?= strtoupper(substr($cliente['nombre'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="cliente-info">
                    <h3><?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?></h3>
                    <p class="text-muted">DNI: <?= htmlspecialchars($cliente['dni']) ?></p>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <label>Email:</label>
                    <span><?= htmlspecialchars($cliente['email'] ?? 'No registrado') ?></span>
                </div>
                <div class="info-item">
                    <label>Teléfono:</label>
                    <span><?= htmlspecialchars($cliente['telefono'] ?? 'No registrado') ?></span>
                </div>
                <div class="info-item">
                    <label>Dirección:</label>
                    <span><?= htmlspecialchars($cliente['direccion'] ?? 'No registrada') ?></span>
                </div>
                <div class="info-item">
                    <label>Fecha de Nacimiento:</label>
                    <span><?= $cliente['fecha_nacimiento'] ? date('d/m/Y', strtotime($cliente['fecha_nacimiento'])) : 'No registrada' ?></span>
                </div>
                <div class="info-item">
                    <label>Fecha de Registro:</label>
                    <span><?= date('d/m/Y H:i', strtotime($cliente['created_at'])) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Membresía Activa -->
    <div class="card">
        <div class="card-header">
            <h2>Membresía Activa</h2>
        </div>
        <div class="card-body">
            <?php if ($membresiaActiva): ?>
                <div class="membresia-active">
                    <div class="membresia-name">
                        <h3><?= htmlspecialchars($membresiaActiva['membresia_nombre']) ?></h3>
                        <span class="badge badge-success">Activa</span>
                    </div>
                    <div class="membresia-dates">
                        <div class="date-item">
                            <label>Inicio:</label>
                            <span><?= date('d/m/Y', strtotime($membresiaActiva['fecha_inicio'])) ?></span>
                        </div>
                        <div class="date-item">
                            <label>Vencimiento:</label>
                            <span><?= date('d/m/Y', strtotime($membresiaActiva['fecha_fin'])) ?></span>
                        </div>
                    </div>
                    <?php
                    $hoy = new DateTime();
                    $vencimiento = new DateTime($membresiaActiva['fecha_fin']);
                    $diasRestantes = $hoy->diff($vencimiento)->days;
                    $vencida = $hoy > $vencimiento;
                    ?>
                    <div class="membresia-status">
                        <?php if ($vencida): ?>
                            <span class="badge badge-danger">Vencida</span>
                        <?php elseif ($diasRestantes <= 7): ?>
                            <span class="badge badge-warning">Por vencer (<?= $diasRestantes ?> días)</span>
                        <?php else: ?>
                            <span class="badge badge-info"><?= $diasRestantes ?> días restantes</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                        <line x1="1" y1="10" x2="23" y2="10" />
                    </svg>
                    <p>No tiene membresía activa</p>
                    <a href="<?= BASE_URL ?>membresias/asignar/<?= $cliente['id'] ?>" class="btn btn-primary btn-sm">
                        Asignar Membresía
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Historial de Asistencias -->
<div class="card">
    <div class="card-header">
        <h2>Últimas Asistencias</h2>
    </div>
    <div class="card-body">
        <?php if (empty($asistencias)): ?>
            <div class="empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <p>No hay asistencias registradas</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha y Hora</th>
                            <th>Registrado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asistencias as $asistencia): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($asistencia['fecha_hora'])) ?></td>
                                <td><?= htmlspecialchars($asistencia['registrado_por']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .cliente-detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .cliente-profile {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }

    .cliente-avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
    }

    .cliente-avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder-large {
        width: 100%;
        height: 100%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: 700;
    }

    .cliente-info h3 {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .info-grid {
        display: grid;
        gap: 15px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .info-item label {
        font-weight: 600;
        color: var(--text-secondary);
    }

    .membresia-active {
        text-align: center;
    }

    .membresia-name {
        margin-bottom: 20px;
    }

    .membresia-name h3 {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .membresia-dates {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .date-item {
        padding: 15px;
        background: var(--bg-secondary);
        border-radius: var(--radius-md);
    }

    .date-item label {
        display: block;
        font-size: 12px;
        color: var(--text-secondary);
        margin-bottom: 5px;
    }

    .date-item span {
        font-weight: 600;
        font-size: 16px;
    }

    .membresia-status {
        margin-top: 15px;
    }

    @media (max-width: 768px) {
        .cliente-detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>