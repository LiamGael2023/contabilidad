<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Contabilidad Perú</title>
    <!-- Tabler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css" rel="stylesheet">
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>
<body class="d-flex flex-column bg-white">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark">
                    <i class="ti ti-calculator icon" style="font-size: 3rem; color: #0D8ABC;"></i>
                </a>
            </div>
            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">Sistema de Contabilidad Perú</h2>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <div class="d-flex">
                                <div>
                                    <i class="ti ti-alert-circle me-2"></i>
                                </div>
                                <div>
                                    <?= htmlspecialchars($_SESSION['error']) ?>
                                    <?php unset($_SESSION['error']); ?>
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/login" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="username" placeholder="Ingrese su usuario" autocomplete="username" required autofocus>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">
                                Contraseña
                            </label>
                            <div class="input-group input-group-flat">
                                <input type="password" class="form-control" name="password" placeholder="Tu contraseña" autocomplete="current-password" required>
                                <span class="input-group-text">
                                    <a href="#" class="link-secondary" title="Mostrar contraseña" data-bs-toggle="tooltip">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input"/>
                                <span class="form-check-label">Recordar sesión en este dispositivo</span>
                            </label>
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-login me-2"></i>
                                Iniciar Sesión
                            </button>
                        </div>
                    </form>
                </div>
                <div class="hr-text">Información</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="text-muted">
                                <strong>Credenciales por defecto:</strong>
                            </div>
                            <div class="text-muted small">
                                <div><i class="ti ti-user me-1"></i> Usuario: <code>admin</code></div>
                                <div><i class="ti ti-key me-1"></i> Contraseña: <code>admin123</code></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center text-muted mt-3">
                ¿No tienes cuenta? Contacta al administrador del sistema.
            </div>
        </div>
    </div>

    <!-- Tabler Core -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
</body>
</html>
