<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <h1><?= $title ?></h1>
        <p>Administra los planes de membresía</p>
    </div>
    <div class="page-header-right">
        <a href="<?= BASE_URL ?>membresias/crear" class="btn btn-primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
            Nueva Membresía
        </a>
    </div>
}</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h2>Lista de Membresías</h2></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Duración (días)</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($membresias)): ?>
                    <tr><td colspan="5" class="text-center">No hay membresías registradas</td></tr>
                <?php else: foreach ($membresias as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['nombre']) ?></td>
                        <td><?= (int)$m['duracion_dias'] ?></td>
                        <td>S/ <?= number_format($m['precio'], 2) ?></td>
                        <td><span class="badge badge-<?= $m['estado']==='activo'?'success':'danger' ?>"><?= ucfirst($m['estado']) ?></span></td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= BASE_URL ?>membresias/editar/<?= $m['id'] ?>" class="btn btn-sm btn-primary" title="Editar">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" /><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
                                </a>
                                <a href="<?= BASE_URL ?>membresias/cambiarEstado/<?= $m['id'] ?>" class="btn btn-sm btn-<?= $m['estado']==='activo'?'danger':'success' ?>" data-confirm="¿Cambiar estado?" title="Estado">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /><line x1="15" y1="9" x2="9" y2="15" /><line x1="9" y1="9" x2="15" y2="15" /></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
}</div>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>

