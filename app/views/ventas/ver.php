<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <h1><?= $title ?></h1>
        <p>Información detallada de la venta</p>
    </div>
    <div class="page-header-right">
        <a href="<?= BASE_URL ?>ventas" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            Volver
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'];
                                        unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Venta #<?= $venta['id'] ?></h2>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group">
                <label><strong>ID Venta:</strong></label>
                <p>#<?= $venta['id'] ?></p>
            </div>
            <div class="form-group">
                <label><strong>Fecha:</strong></label>
                <p><?= date('d/m/Y H:i:s', strtotime($venta['fecha_venta'])) ?></p>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label><strong>Cliente:</strong></label>
                <p><?= $venta['cliente_nombre'] ?: '<em>Cliente general</em>' ?></p>
                <?php if (!empty($venta['cliente_dni'])): ?>
                    <p style="color: var(--text-secondary); font-size: 13px;">DNI: <?= $venta['cliente_dni'] ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label><strong>Método de Pago:</strong></label>
                <p><span class="badge badge-info"><?= ucfirst($venta['metodo_pago']) ?></span></p>
            </div>
        </div>

        <div class="form-group">
            <label><strong>Vendedor:</strong></label>
            <p><?= htmlspecialchars($venta['usuario_nombre']) ?></p>
        </div>

        <hr style="margin: 30px 0;">

        <h3>Productos Vendidos</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $d): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <?php if (!empty($d['producto_imagen'])): ?>
                                        <img src="<?= BASE_URL ?>public/uploads/<?= $d['producto_imagen'] ?>" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    <?php endif; ?>
                                    <span><?= htmlspecialchars($d['producto_nombre']) ?></span>
                                </div>
                            </td>
                            <td>S/ <?= number_format($d['precio_unitario'], 2) ?></td>
                            <td><?= $d['cantidad'] ?></td>
                            <td><strong>S/ <?= number_format($d['subtotal'], 2) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>TOTAL:</strong></td>
                        <td><strong style="font-size: 18px; color: var(--primary-color);">S/ <?= number_format($venta['total'], 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div style="margin-top: 20px;">
            <a href="<?= BASE_URL ?>ventas/eliminar/<?= $venta['id'] ?>"
                class="btn btn-danger"
                onclick="return confirm('¿Está seguro de eliminar esta venta? Se restaurará el stock de los productos.')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                Eliminar Venta
            </a>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>