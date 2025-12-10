<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <h1><?= $title ?></h1>
        <p>Administra los clientes del gimnasio</p>
    </div>
    <div class="page-header-right">
        <a href="<?= BASE_URL ?>clientes/crear" class="btn btn-primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Nuevo Cliente
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success'];
        unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Lista de Clientes</h2>
        <div class="search-box">
            <input type="text" id="searchClientes" placeholder="Buscar por nombre, apellido o DNI..." class="form-control">
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>DNI</th>
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Membresía</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="clientesTableBody">
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay clientes registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($cliente['foto'])): ?>
                                        <img src="<?= BASE_URL ?>public/uploads/<?= $cliente['foto'] ?>"
                                            alt="Foto" class="table-avatar">
                                    <?php else: ?>
                                        <div class="table-avatar-placeholder">
                                            <?= strtoupper(substr($cliente['nombre'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($cliente['dni']) ?></td>
                                <td><?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?></td>
                                <td><?= htmlspecialchars($cliente['telefono'] ?? '-') ?></td>
                                <td>
                                    <?php if (!empty($cliente['membresia_nombre'])): ?>
                                        <span class="badge badge-<?= $cliente['membresia_estado'] === 'activa' ? 'success' : 'danger' ?>">
                                            <?= htmlspecialchars($cliente['membresia_nombre']) ?>
                                        </span>
                                        <?php if ($cliente['membresia_estado'] === 'activa'): ?>
                                            <small class="text-muted">
                                                Vence: <?= date('d/m/Y', strtotime($cliente['fecha_fin'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Sin membresía</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $cliente['estado'] === 'activo' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($cliente['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>clientes/ver/<?= $cliente['id'] ?>"
                                            class="btn btn-sm btn-secondary" title="Ver detalles">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                        <a href="<?= BASE_URL ?>clientes/editar/<?= $cliente['id'] ?>"
                                            class="btn btn-sm btn-primary" title="Editar">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                        <a href="<?= BASE_URL ?>clientes/cambiarEstado/<?= $cliente['id'] ?>"
                                            class="btn btn-sm btn-<?= $cliente['estado'] === 'activo' ? 'danger' : 'success' ?>"
                                            data-confirm="¿Está seguro de cambiar el estado?"
                                            title="<?= $cliente['estado'] === 'activo' ? 'Desactivar' : 'Activar' ?>">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10" />
                                                <line x1="15" y1="9" x2="9" y2="15" />
                                                <line x1="9" y1="9" x2="15" y2="15" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (($totalPages ?? 1) > 1): ?>
    <div class="pagination">
        <?php $prev = max(1, ($page ?? 1) - 1);
        $next = min(($totalPages ?? 1), ($page ?? 1) + 1); ?>
        <a class="page-link <?= ($page ?? 1) <= 1 ? 'disabled' : '' ?>" href="<?= BASE_URL ?>clientes?page=<?= $prev ?>">« Anterior</a>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="page-link <?= ($page == $i) ? 'active' : '' ?>" href="<?= BASE_URL ?>clientes?page=<?= $i ?>"><?= $i ?></a>
        <?php endfor; ?>
        <a class="page-link <?= ($page ?? 1) >= ($totalPages ?? 1) ? 'disabled' : '' ?>" href="<?= BASE_URL ?>clientes?page=<?= $next ?>">Siguiente »</a>
    </div>
<?php endif; ?>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-header-left h1 {
        margin-bottom: 5px;
    }

    .page-header-left p {
        color: var(--text-secondary);
        font-size: 14px;
    }

    .search-box {
        min-width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
    }

    .table-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .table-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .btn-group {
        display: flex;
        gap: 5px;
    }

    .text-center {
        text-align: center;
    }

    .text-muted {
        color: var(--text-secondary);
        font-size: 12px;
        display: block;
    }

    .pagination {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-top: 20px;
    }

    .page-link {
        padding: 6px 10px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--text-color);
        background: #fff;
    }

    .page-link.active {
        background: var(--primary-color);
        color: #fff;
        border-color: var(--primary-color);
    }

    .page-link.disabled {
        pointer-events: none;
        opacity: 0.5;
    }
</style>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>
<script>
    (function(){
        var input = document.getElementById('searchClientes');
        if(!input) return;
        var tbody = document.getElementById('clientesTableBody');
        var pagination = document.querySelector('.pagination');
        var initialHTML = tbody ? tbody.innerHTML : '';
        var BASE = '<?= BASE_URL ?>';
        var timer;
        function h(s){
            s = s==null ? '' : String(s);
            return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
        }
        function renderRows(items){
            if(!items || !items.length){
                return '<tr><td colspan="7" class="text-center">No hay clientes</td></tr>';
            }
            var out = '';
            for(var i=0;i<items.length;i++){
                var c = items[i];
                var foto = c.foto ? '<img src="'+BASE+'public/uploads/'+h(c.foto)+'" alt="Foto" class="table-avatar">' : '<div class="table-avatar-placeholder">'+h((c.nombre||'').toUpperCase().slice(0,1))+'</div>';
                var membBadge;
                if(c.membresia_nombre){
                    var badgeClass = c.membresia_estado==='activa' ? 'success' : 'danger';
                    membBadge = '<span class="badge badge-'+badgeClass+'">'+h(c.membresia_nombre)+'</span>'+(c.membresia_estado==='activa' && c.fecha_fin ? '<small class="text-muted">Vence: '+h(new Date(c.fecha_fin).toLocaleDateString())+'</small>' : '');
                } else {
                    membBadge = '<span class="badge badge-warning">Sin membresía</span>';
                }
                var estadoClass = c.estado==='activo' ? 'success' : 'danger';
                var accionEstadoClass = c.estado==='activo' ? 'danger' : 'success';
                var accionTitulo = c.estado==='activo' ? 'Desactivar' : 'Activar';
                out += '<tr>'+
                    '<td>'+foto+'</td>'+
                    '<td>'+h(c.dni)+'</td>'+
                    '<td>'+h((c.nombre||'')+' '+(c.apellido||''))+'</td>'+
                    '<td>'+h(c.telefono||'-')+'</td>'+
                    '<td>'+membBadge+'</td>'+
                    '<td><span class="badge badge-'+estadoClass+'">'+h((c.estado||'').charAt(0).toUpperCase()+ (c.estado||'').slice(1))+'</span></td>'+
                    '<td>'+
                        '<div class="btn-group">'+
                            '<a href="'+BASE+'clientes/ver/'+h(c.id)+'" class="btn btn-sm btn-secondary" title="Ver detalles">'+
                                '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" /></svg>'+
                            '</a>'+
                            '<a href="'+BASE+'clientes/editar/'+h(c.id)+'" class="btn btn-sm btn-primary" title="Editar">'+
                                '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" /><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>'+
                            '</a>'+
                            '<a href="'+BASE+'clientes/cambiarEstado/'+h(c.id)+'" class="btn btn-sm btn-'+accionEstadoClass+'" data-confirm="¿Está seguro de cambiar el estado?" title="'+accionTitulo+'">'+
                                '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /><line x1="15" y1="9" x2="9" y2="15" /><line x1="9" y1="9" x2="15" y2="15" /></svg>'+
                            '</a>'+
                        '</div>'+
                    '</td>'+
                '</tr>';
            }
            return out;
        }
        function doSearch(term){
            if(!tbody) return;
            if(term.length < 2){
                tbody.innerHTML = initialHTML;
                if(pagination) pagination.style.display = '';
                return;
            }
            if(pagination) pagination.style.display = 'none';
            fetch(BASE+'clientes/buscar?q='+encodeURIComponent(term))
                .then(function(r){ return r.json(); })
                .then(function(data){
                    var items = (data && data.results) ? data.results : [];
                    tbody.innerHTML = renderRows(items);
                })
                .catch(function(){
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center">Error al buscar</td></tr>';
                });
        }
        input.addEventListener('input', function(){
            clearTimeout(timer);
            var val = this.value.trim();
            timer = setTimeout(function(){ doSearch(val); }, 300);
        });
    })();
</script>
