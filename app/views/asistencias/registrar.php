<?php require_once APP_PATH . 'views/layouts/header.php'; ?>

<div class="page-header">
    <div class="page-header-left">
        <h1><?= $title ?></h1>
        <p>Registra la entrada de los clientes al gimnasio</p>
    </div>
    <div class="page-header-right">
        <a href="<?= BASE_URL ?>asistencias" class="btn btn-secondary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 11l3 3L22 4" />
                <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
            </svg>
            Ver Asistencias
        </a>
    </div>
</div>

<div class="registro-asistencia-container">
    <div class="card registro-card">
        <div class="card-body">
            <div class="registro-icon">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
            </div>

            <h2>Registrar Asistencia</h2>
            <p class="text-muted">Ingresa el DNI del cliente para registrar su entrada</p>

            <form id="formAsistencia" class="registro-form">
                <div class="form-group">
                    <input
                        type="text"
                        id="dni"
                        name="dni"
                        placeholder="Ingrese DNI del cliente"
                        class="form-control-large"
                        autofocus
                        required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    Registrar Entrada
                </button>
            </form>

            <div id="resultado" class="resultado-mensaje"></div>
        </div>
    </div>
</div>

<style>
    .registro-asistencia-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .registro-card {
        text-align: center;
    }

    .registro-icon {
        display: inline-flex;
        width: 120px;
        height: 120px;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border-radius: 50%;
        color: white;
        margin-bottom: 30px;
    }

    .registro-card h2 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .registro-form {
        margin-top: 30px;
    }

    .form-control-large {
        width: 100%;
        padding: 16px 20px;
        font-size: 18px;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        text-align: center;
        transition: all 0.2s;
    }

    .form-control-large:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .btn-lg {
        padding: 14px 24px;
        font-size: 16px;
        margin-top: 20px;
    }

    .resultado-mensaje {
        margin-top: 20px;
        padding: 16px;
        border-radius: var(--radius-md);
        display: none;
    }

    .resultado-mensaje.success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        display: block;
    }

    .resultado-mensaje.error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
        display: block;
    }

    .resultado-mensaje h3 {
        margin-bottom: 5px;
        font-size: 18px;
    }
</style>

<script>
    document.getElementById('formAsistencia').addEventListener('submit', async function(e) {
        e.preventDefault();

        const dni = document.getElementById('dni').value;
        const resultado = document.getElementById('resultado');

        try {
            const response = await fetch('<?= BASE_URL ?>asistencias/registrar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'dni=' + encodeURIComponent(dni)
            });

            const data = await response.json();

            if (data.success) {
                resultado.className = 'resultado-mensaje success';
                resultado.innerHTML = '<h3>✓ Asistencia Registrada</h3><p>' + data.cliente + '</p>';
                document.getElementById('dni').value = '';

                setTimeout(() => {
                    resultado.style.display = 'none';
                }, 3000);
            } else {
                resultado.className = 'resultado-mensaje error';
                resultado.innerHTML = '<h3>✗ Error</h3><p>' + data.message + '</p>';
            }
        } catch (error) {
            resultado.className = 'resultado-mensaje error';
            resultado.innerHTML = '<h3>✗ Error</h3><p>Error de conexión</p>';
        }
    });
</script>

<?php require_once APP_PATH . 'views/layouts/footer.php'; ?>