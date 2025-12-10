<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
        </div>
        <div class="stat-content">
            <h3><?= $clientesActivos ?></h3>
            <p>Clientes Activos</p>
        </div>
    </div>

    <div class="stat-card stat-success">
        <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23" />
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
        </div>
        <div class="stat-content">
            <h3>S/ <?= number_format($ingresosMes, 2) ?></h3>
            <p>Ingresos del Mes</p>
        </div>
    </div>

    <div class="stat-card stat-info">
        <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
        </div>
        <div class="stat-content">
            <h3><?= $asistenciasHoy ?></h3>
            <p>Asistencias Hoy</p>
        </div>
    </div>

    <div class="stat-card stat-warning">
        <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                <line x1="12" y1="9" x2="12" y2="13" />
                <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
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