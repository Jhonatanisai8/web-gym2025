<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistema de Gimnasio' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>

<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="logo">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 6h4v12H6zM14 6h4v12h-4zM2 9h2M20 9h2M2 15h2M20 15h2" />
                    </svg>
                </div>
                <h1>Sistema de Gestión</h1>
                <p>Gimnasio</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>auth/login" class="login-form">
                <div class="form-group">
                    <label for="email">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="correo@ejemplo.com"
                        required
                        autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        Contraseña
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password">
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Iniciar Sesión
                </button>
            </form>

            <div class="login-footer">
                <p>Credenciales por defecto:</p>
                <small>Email: <strong>admin@gimnasio.com</strong> | Password: <strong>admin123</strong></small>
            </div>
        </div>
    </div>
</body>

</html>