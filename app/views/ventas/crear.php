<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <h1><?= $title ?></h1>
        <p>Registrar nueva venta</p>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 20px;">
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Información de la Venta</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>ventas/crear" class="form" id="formVenta">
            <div class="form-row">
                <div class="form-group">
                    <label>Cliente (Opcional)</label>
                    <select name="cliente_id" id="cliente_id">
                        <option value="">-- Cliente general --</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>">
                                <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?> - DNI: <?= $c['dni'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Método de Pago <span style="color: red;">*</span></label>
                    <select name="metodo_pago" id="metodo_pago" required>
                        <option value="">-- Seleccione --</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="yape">Yape</option>
                        <option value="plin">Plin</option>
                    </select>
                </div>
            </div>

            <hr style="margin: 20px 0;">

            <div class="form-group">
                <label>Seleccionar Producto</label>
                <select id="producto_select" class="form-control">
                    <option value="">-- Seleccione un producto --</option>
                    <?php foreach ($productos as $p): ?>
                        <option value="<?= $p['id'] ?>"
                            data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                            data-precio="<?= $p['precio'] ?>"
                            data-stock="<?= $p['stock'] ?>">
                            <?= htmlspecialchars($p['nombre']) ?> - S/ <?= number_format($p['precio'], 2) ?> (Stock: <?= $p['stock'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Cantidad</label>
                    <input type="number" id="cantidad_input" min="1" value="1">
                </div>
                <div class="form-group" style="display: flex; align-items: flex-end;">
                    <button type="button" id="btnAgregar" class="btn btn-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Agregar al Carrito
                    </button>
                </div>
            </div>

            <hr style="margin: 30px 0;">

            <h3>Carrito de Compras</h3>
            <div class="table-responsive">
                <table class="table" id="tablaCarrito">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="carritoBody">
                        <tr id="carritoVacio">
                            <td colspan="5" class="text-center">No hay productos en el carrito</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>TOTAL:</strong></td>
                            <td colspan="2"><strong id="totalVenta">S/ 0.00</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <input type="hidden" name="productos_json" id="productos_json">
            <input type="hidden" name="total" id="total_hidden">

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" id="btnGuardar">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Guardar Venta
                </button>
                <a href="<?= BASE_URL ?>ventas" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
    let carrito = [];

    document.getElementById('btnAgregar').addEventListener('click', function() {
        const select = document.getElementById('producto_select');
        const cantidad = parseInt(document.getElementById('cantidad_input').value);

        if (!select.value) {
            alert('Seleccione un producto');
            return;
        }

        if (cantidad <= 0) {
            alert('La cantidad debe ser mayor a 0');
            return;
        }

        const option = select.options[select.selectedIndex];
        const productoId = parseInt(select.value);
        const nombre = option.dataset.nombre;
        const precio = parseFloat(option.dataset.precio);
        const stock = parseInt(option.dataset.stock);

        if (cantidad > stock) {
            alert(`Stock insuficiente. Disponible: ${stock}`);
            return;
        }

        // Verificar si el producto ya está en el carrito
        const existente = carrito.find(item => item.producto_id === productoId);
        if (existente) {
            if (existente.cantidad + cantidad > stock) {
                alert(`Stock insuficiente. Ya tiene ${existente.cantidad} en el carrito. Disponible: ${stock}`);
                return;
            }
            existente.cantidad += cantidad;
            existente.subtotal = existente.cantidad * existente.precio_unitario;
        } else {
            carrito.push({
                producto_id: productoId,
                nombre: nombre,
                precio_unitario: precio,
                cantidad: cantidad,
                subtotal: precio * cantidad
            });
        }

        actualizarCarrito();

        // Resetear selección
        select.value = '';
        document.getElementById('cantidad_input').value = 1;
    });

    function actualizarCarrito() {
        const tbody = document.getElementById('carritoBody');
        const carritoVacio = document.getElementById('carritoVacio');

        if (carrito.length === 0) {
            carritoVacio.style.display = '';
            tbody.querySelectorAll('tr:not(#carritoVacio)').forEach(tr => tr.remove());
            document.getElementById('totalVenta').textContent = 'S/ 0.00';
            document.getElementById('total_hidden').value = '0';
            document.getElementById('productos_json').value = '';
            return;
        }

        carritoVacio.style.display = 'none';
        tbody.querySelectorAll('tr:not(#carritoVacio)').forEach(tr => tr.remove());

        let total = 0;
        carrito.forEach((item, index) => {
            total += item.subtotal;

            const tr = document.createElement('tr');
            tr.innerHTML = `
            <td>${item.nombre}</td>
            <td>S/ ${item.precio_unitario.toFixed(2)}</td>
            <td>${item.cantidad}</td>
            <td>S/ ${item.subtotal.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="eliminarItem(${index})">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
            </td>
        `;
            tbody.appendChild(tr);
        });

        document.getElementById('totalVenta').textContent = 'S/ ' + total.toFixed(2);
        document.getElementById('total_hidden').value = total.toFixed(2);
        document.getElementById('productos_json').value = JSON.stringify(carrito);
    }

    function eliminarItem(index) {
        carrito.splice(index, 1);
        actualizarCarrito();
    }

    document.getElementById('formVenta').addEventListener('submit', function(e) {
        if (carrito.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un producto al carrito');
            return false;
        }

        const metodoPago = document.getElementById('metodo_pago').value;
        if (!metodoPago) {
            e.preventDefault();
            alert('Debe seleccionar un método de pago');
            return false;
        }
    });
</script>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>