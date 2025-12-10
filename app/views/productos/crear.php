<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left"><h1><?= $title ?></h1></div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>productos/crear" class="form" enctype="multipart/form-data">
            <div class="form-group"><label>Nombre</label><input type="text" name="nombre" value="<?= htmlspecialchars($old['nombre'] ?? '') ?>" class="form-control" required></div>
            <div class="form-group"><label>Descripción</label><textarea name="descripcion" class="form-control"><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea></div>
            <div class="form-group">
                <label>Categoría</label>
                <select name="categoria_id" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($categorias as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= (isset($old['categoria_id']) && $old['categoria_id']==$c['id'])?'selected':'' ?>><?= htmlspecialchars($c['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Precio</label><input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($old['precio'] ?? '') ?>" class="form-control" required></div>
            <div class="form-group"><label>Stock</label><input type="number" name="stock" value="<?= htmlspecialchars($old['stock'] ?? '0') ?>" class="form-control"></div>
            <div class="form-group"><label>Stock mínimo</label><input type="number" name="stock_minimo" value="<?= htmlspecialchars($old['stock_minimo'] ?? '5') ?>" class="form-control"></div>
            <div class="form-group"><label>Imagen</label><input type="file" name="imagen" class="form-control"></div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="<?= BASE_URL ?>productos" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>

