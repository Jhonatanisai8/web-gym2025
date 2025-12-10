<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header"><div class="page-header-left"><h1><?= $title ?></h1></div></div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>pagos/crear" class="form">
            <div class="form-group">
                <label>Cliente</label>
                <select name="cliente_id" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= (isset($old['cliente_id']) && $old['cliente_id']==$c['id'])?'selected':'' ?>><?= htmlspecialchars($c['nombre'].' '.$c['apellido'].' - '.$c['dni']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Membresía</label>
                <select name="membresia_id" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($membresias as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= (isset($old['membresia_id']) && $old['membresia_id']==$m['id'])?'selected':'' ?>><?= htmlspecialchars($m['nombre'].' - S/ '.number_format($m['precio'],2)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Método de pago</label>
                <select name="metodo_pago" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach (['efectivo','tarjeta','transferencia','yape','plin'] as $m): ?>
                        <option value="<?= $m ?>" <?= (isset($old['metodo_pago']) && $old['metodo_pago']==$m)?'selected':'' ?>><?= ucfirst($m) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control"><?= htmlspecialchars($old['observaciones'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Pago</button>
            <a href="<?= BASE_URL ?>pagos" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>

