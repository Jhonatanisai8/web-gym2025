<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-icon">
            <img src="<?= BASE_URL ?>public/img/icons8-clientes-50.png" alt="Clientes" width="32" height="32">
        </div>
        <div class="stat-content">
            <h3><?= $clientesActivos ?></h3>
            <p>Clientes Activos</p>
        </div>
    </div>

    <div class="stat-card stat-success">
        <div class="stat-icon">
            <img src="<?= BASE_URL ?>public/img/icons8-ingresos-50.png" alt="Ingresos" width="32" height="32">
        </div>
        <div class="stat-content">
            <h3>S/ <?= number_format($ingresosMes, 2) ?></h3>
            <p>Ingresos del Mes</p>
        </div>
    </div>

    <div class="stat-card stat-info">
        <div class="stat-icon">
            <img src="<?= BASE_URL ?>public/img/icons8-asistencia-50.png" alt="Asistencias" width="32" height="32">
        </div>
        <div class="stat-content">
            <h3><?= $asistenciasHoy ?></h3>
            <p>Asistencias Hoy</p>
        </div>
    </div>

    <div class="stat-card stat-warning">
        <div class="stat-icon">
            <img src="<?= BASE_URL ?>public/img/icons8-advertencia-50.png" alt="Advertencia" width="32" height="32">
        </div>
        <div class="stat-content">
            <h3><?= $productosStockBajo ?></h3>
            <p>Productos Stock Bajo</p>
        </div>
    </div>
</div>

<!-- Membresías por Vencer -->
<div class="card">
    <div class="card-header">
        <h2>Membresías Próximas a Vencer</h2>
        <span class="badge badge-warning"><?= count($membresiasPorVencer) ?> alertas</span>
    </div>
    <div class="card-body">
        <?php if (empty($membresiasPorVencer)): ?>
            <div class="empty-state">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <p>No hay membresías próximas a vencer</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Membresía</th>
                            <th>Fecha de Vencimiento</th>
                            <th>Días Restantes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($membresiasPorVencer as $item):
                            $fechaVencimiento = new DateTime($item['fecha_fin']);
                            $hoy = new DateTime();
                            $diasRestantes = $hoy->diff($fechaVencimiento)->days;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nombre'] . ' ' . $item['apellido']) ?></td>
                                <td><?= htmlspecialchars($item['membresia']) ?></td>
                                <td><?= date('d/m/Y', strtotime($item['fecha_fin'])) ?></td>
                                <td>
                                    <span class="badge badge-<?= $diasRestantes <= 3 ? 'danger' : 'warning' ?>">
                                        <?= $diasRestantes ?> día<?= $diasRestantes != 1 ? 's' : '' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>