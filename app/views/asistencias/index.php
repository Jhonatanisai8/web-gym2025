<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <h1><?= $title ?></h1>
        <p>Historial de asistencias del día</p>
    </div>
    <div class="page-header-right">
        <input type="date" id="fecha" value="<?= $fecha ?>" class="form-control"
            onchange="window.location.href='<?= BASE_URL ?>asistencias?fecha=' + this.value">
        <a href="<?= BASE_URL ?>asistencias/registrar" class="btn btn-primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Registrar Asistencia
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Asistencias del <?= date('d/m/Y', strtotime($fecha)) ?></h2>
        <span class="badge badge-primary"><?= count($asistencias) ?> registros</span>
    </div>
    <div class="card-body">
        <?php if (empty($asistencias)): ?>
            <div class="empty-state">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <p>No hay asistencias registradas para esta fecha</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>DNI</th>
                            <th>Cliente</th>
                            <th>Registrado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asistencias as $asistencia): ?>
                            <tr>
                                <td><?= date('H:i', strtotime($asistencia['fecha_hora'])) ?></td>
                                <td><?= htmlspecialchars($asistencia['dni']) ?></td>
                                <td><?= htmlspecialchars($asistencia['nombre'] . ' ' . $asistencia['apellido']) ?></td>
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
    .page-header-right {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .page-header-right input[type="date"] {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
    }
</style>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>