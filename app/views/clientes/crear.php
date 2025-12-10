<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <a href="<?= BASE_URL ?>clientes" class="btn btn-secondary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12" />
                <polyline points="12 19 5 12 12 5" />
            </svg>
            Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2><?= $title ?></h2>
    </div>
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="dni">DNI *</label>
                    <input type="text" id="dni" name="dni"
                        value="<?= htmlspecialchars($old['dni'] ?? '') ?>"
                        required maxlength="20">
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono"
                        value="<?= htmlspecialchars($old['telefono'] ?? '') ?>"
                        maxlength="20">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre *</label>
                    <input type="text" id="nombre" name="nombre"
                        value="<?= htmlspecialchars($old['nombre'] ?? '') ?>"
                        required maxlength="100">
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido *</label>
                    <input type="text" id="apellido" name="apellido"
                        value="<?= htmlspecialchars($old['apellido'] ?? '') ?>"
                        required maxlength="100">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                        maxlength="100">
                </div>

                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                        value="<?= htmlspecialchars($old['fecha_nacimiento'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion"
                    value="<?= htmlspecialchars($old['direccion'] ?? '') ?>"
                    maxlength="255">
            </div>

            <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" id="foto" name="foto" accept="image/*">
                <small class="form-text">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Guardar Cliente
                </button>
                <a href="<?= BASE_URL ?>clientes" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<style>
    .form-text {
        display: block;
        margin-top: 5px;
        color: var(--text-secondary);
        font-size: 12px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }
</style>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>