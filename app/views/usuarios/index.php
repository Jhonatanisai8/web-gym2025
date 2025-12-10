<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <h1><?= $title ?></h1>
        <p>Gestión de usuarios del sistema</p>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h2>Usuarios</h2></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr><td colspan="5" class="text-center">No hay usuarios</td></tr>
                <?php else: foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['rol_nombre']) ?></td>
                        <td><span class="badge badge-<?= $u['estado']==='activo'?'success':'danger' ?>"><?= ucfirst($u['estado']) ?></span></td>
                        <td>
                            <div class="btn-group">
                                <?php $nuevo = $u['estado']==='activo'?'inactivo':'activo'; ?>
                                <a href="<?= BASE_URL ?>usuarios/cambiarEstado/<?= $u['id'] ?>?estado=<?= $nuevo ?>" class="btn btn-sm btn-<?= $u['estado']==='activo'?'danger':'success' ?>" data-confirm="¿Cambiar estado?" title="Estado">
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
</div>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>

