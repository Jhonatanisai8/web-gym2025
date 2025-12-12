<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 20px;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Asignar Membresía a <?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?></h2>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label for="membresia_id">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                        Membresía *
                    </label>
                    <select name="membresia_id" id="membresia_id" required>
                        <option value="">Seleccione una membresía</option>
                        <?php foreach ($membresias as $membresia): ?>
                            <option
                                value="<?= $membresia['id'] ?>"
                                <?= (isset($old['membresia_id']) && $old['membresia_id'] == $membresia['id']) ? 'selected' : '' ?>
                                data-duracion="<?= $membresia['duracion_dias'] ?>"
                                data-precio="<?= $membresia['precio'] ?>">
                                <?= htmlspecialchars($membresia['nombre']) ?>
                                (<?= $membresia['duracion_dias'] ?> días - S/. <?= number_format($membresia['precio'], 2) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_inicio">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        Fecha de Inicio *
                    </label>
                    <input
                        type="date"
                        name="fecha_inicio"
                        id="fecha_inicio"
                        value="<?= $old['fecha_inicio'] ?? date('Y-m-d') ?>"
                        required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="metodo_pago">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                        Método de Pago *
                    </label>
                    <select name="metodo_pago" id="metodo_pago" required>
                        <option value="">Seleccione un método</option>
                        <option value="efectivo" <?= (isset($old['metodo_pago']) && $old['metodo_pago'] == 'efectivo') ? 'selected' : '' ?>>Efectivo</option>
                        <option value="tarjeta" <?= (isset($old['metodo_pago']) && $old['metodo_pago'] == 'tarjeta') ? 'selected' : '' ?>>Tarjeta</option>
                        <option value="transferencia" <?= (isset($old['metodo_pago']) && $old['metodo_pago'] == 'transferencia') ? 'selected' : '' ?>>Transferencia</option>
                        <option value="yape" <?= (isset($old['metodo_pago']) && $old['metodo_pago'] == 'yape') ? 'selected' : '' ?>>Yape</option>
                        <option value="plin" <?= (isset($old['metodo_pago']) && $old['metodo_pago'] == 'plin') ? 'selected' : '' ?>>Plin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Fecha de Vencimiento (Calculada)</label>
                    <input type="text" id="fecha_fin_display" readonly style="background: var(--bg-secondary); cursor: not-allowed;" value="Seleccione una membresía">
                </div>
            </div>

            <div class="info-box">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="16" x2="12" y2="12" />
                    <line x1="12" y1="8" x2="12.01" y2="8" />
                </svg>
                <div>
                    <strong>Información:</strong>
                    <ul>
                        <li>Al asignar esta membresía, cualquier membresía activa anterior será marcada como vencida.</li>
                        <li>Se registrará automáticamente el pago correspondiente.</li>
                        <li>La fecha de vencimiento se calculará automáticamente según la duración de la membresía.</li>
                    </ul>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= BASE_URL ?>clientes/ver/<?= $cliente['id'] ?>" class="btn btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12" />
                        <polyline points="12 19 5 12 12 5" />
                    </svg>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    Asignar Membresía
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .info-box {
        background: rgba(59, 130, 246, 0.1);
        border-left: 4px solid var(--info-color);
        padding: 15px 20px;
        margin: 20px 0;
        border-radius: var(--radius-md);
        display: flex;
        gap: 15px;
    }

    .info-box svg {
        color: var(--info-color);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .info-box ul {
        margin: 8px 0 0 0;
        padding-left: 20px;
    }

    .info-box li {
        margin: 5px 0;
        color: var(--text-secondary);
    }

    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const membresiaSelect = document.getElementById('membresia_id');
        const fechaInicioInput = document.getElementById('fecha_inicio');
        const fechaFinDisplay = document.getElementById('fecha_fin_display');

        function calcularFechaFin() {
            const selectedOption = membresiaSelect.options[membresiaSelect.selectedIndex];
            const fechaInicio = fechaInicioInput.value;

            if (selectedOption.value && fechaInicio) {
                const duracionDias = parseInt(selectedOption.dataset.duracion);
                const fecha = new Date(fechaInicio);
                fecha.setDate(fecha.getDate() + duracionDias);

                const dia = String(fecha.getDate()).padStart(2, '0');
                const mes = String(fecha.getMonth() + 1).padStart(2, '0');
                const anio = fecha.getFullYear();

                fechaFinDisplay.value = `${dia}/${mes}/${anio}`;
            } else {
                fechaFinDisplay.value = 'Seleccione una membresía y fecha';
            }
        }

        membresiaSelect.addEventListener('change', calcularFechaFin);
        fechaInicioInput.addEventListener('change', calcularFechaFin);

        // Calcular al cargar si hay valores previos
        calcularFechaFin();
    });
</script>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>