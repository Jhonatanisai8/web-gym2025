<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <h1><?= $title ?></h1>
        <p>Registro de ventas de productos</p>
    </div>
    <div class="page-header-right">
        <a href="<?= BASE_URL ?>ventas/crear" class="btn btn-primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Nueva Venta
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'];
                                        unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= $_SESSION['error'];
                                    unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Lista de Ventas</h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Método Pago</th>
                        <th>Vendedor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ventas)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay ventas registradas</td>
                        </tr>
                        <?php else: foreach ($ventas as $v): ?>
                            <tr>
                                <td><strong>#<?= $v['id'] ?></strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($v['fecha_venta'])) ?></td>
                                <td><?= $v['cliente_nombre'] ?: '<em>Cliente general</em>' ?></td>
                                <td><strong>S/ <?= number_format($v['total'], 2) ?></strong></td>
                                <td><span class="badge badge-info"><?= ucfirst($v['metodo_pago']) ?></span></td>
                                <td><?= htmlspecialchars($v['usuario_nombre']) ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>ventas/ver/<?= $v['id'] ?>" class="btn btn-sm btn-primary" title="Ver detalle">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                        <a href="<?= BASE_URL ?>ventas/eliminar/<?= $v['id'] ?>" class="btn btn-sm btn-danger" data-confirm="¿Eliminar esta venta? Se restaurará el stock" title="Eliminar">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                    <?php endforeach;
                    endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPaginas > 1): ?>
            <div class="pagination">
                <div class="pagination-info">
                    Mostrando <?= count($ventas) ?> de <?= $totalVentas ?> ventas
                </div>
                <div class="pagination-controls">
                    <?php if ($paginaActual > 1): ?>
                        <a href="<?= BASE_URL ?>ventas?page=<?= $paginaActual - 1 ?>" class="btn btn-sm btn-secondary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            Anterior
                        </a>
                    <?php else: ?>
                        <span class="btn btn-sm btn-secondary disabled">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            Anterior
                        </span>
                    <?php endif; ?>

                    <div class="pagination-numbers">
                        <?php
                        $rango = 2;
                        $inicio = max(1, $paginaActual - $rango);
                        $fin = min($totalPaginas, $paginaActual + $rango);

                        if ($inicio > 1): ?>
                            <a href="<?= BASE_URL ?>ventas?page=1" class="btn btn-sm btn-secondary">1</a>
                            <?php if ($inicio > 2): ?>
                                <span class="pagination-ellipsis">...</span>
                            <?php endif;
                        endif;

                        for ($i = $inicio; $i <= $fin; $i++):
                            if ($i == $paginaActual): ?>
                                <span class="btn btn-sm btn-primary active"><?= $i ?></span>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>ventas?page=<?= $i ?>" class="btn btn-sm btn-secondary"><?= $i ?></a>
                            <?php endif;
                        endfor;

                        if ($fin < $totalPaginas):
                            if ($fin < $totalPaginas - 1): ?>
                                <span class="pagination-ellipsis">...</span>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>ventas?page=<?= $totalPaginas ?>" class="btn btn-sm btn-secondary"><?= $totalPaginas ?></a>
                        <?php endif; ?>
                    </div>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <a href="<?= BASE_URL ?>ventas?page=<?= $paginaActual + 1 ?>" class="btn btn-sm btn-secondary">
                            Siguiente
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    <?php else: ?>
                        <span class="btn btn-sm btn-secondary disabled">
                            Siguiente
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>