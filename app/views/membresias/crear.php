<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <h1><?= $title ?></h1>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>membresias/crear" class="form">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($old['nombre'] ?? '') ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control"><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label>Duración (días)</label>
                <input type="number" name="duracion_dias" value="<?= htmlspecialchars($old['duracion_dias'] ?? '') ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Precio</label>
                <input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($old['precio'] ?? '') ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="<?= BASE_URL ?>membresias" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>

