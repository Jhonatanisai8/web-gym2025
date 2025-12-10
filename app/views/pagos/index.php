<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <h1><?= $title ?></h1>
        <p>Historial de pagos</p>
    </div>
    <div class="page-header-right">
        <a href="<?= BASE_URL ?>pagos/crear" class="btn btn-primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
            Nuevo Pago
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h2>Pagos</h2></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Membresía</th>
                        <th>Método</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($pagos)): ?>
                    <tr><td colspan="5" class="text-center">Sin pagos</td></tr>
                <?php else: foreach ($pagos as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['cliente_nombre'].' '.$p['cliente_apellido']) ?></td>
                        <td><?= htmlspecialchars($p['membresia_nombre'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['metodo_pago']) ?></td>
                        <td>S/ <?= number_format($p['monto'], 2) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($p['fecha_pago'])) ?></td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>

