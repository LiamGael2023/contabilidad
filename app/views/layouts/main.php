<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistema de Contabilidad' ?> - Sistema de Contabilidad Perú</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/dashboard">
                <i class="bi bi-calculator"></i> Sistema Contable Perú
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard"><i class="bi bi-house"></i> Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarAsientos" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-journal-text"></i> Contabilidad
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/asientos">Asientos Contables</a></li>
                            <li><a class="dropdown-item" href="/plan-contable">Plan Contable</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/libros/diario">Libro Diario</a></li>
                            <li><a class="dropdown-item" href="/libros/mayor">Libro Mayor</a></li>
                            <li><a class="dropdown-item" href="/libros/caja-bancos">Caja y Bancos</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/comprobantes"><i class="bi bi-receipt"></i> Comprobantes</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarReportes" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-graph-up"></i> Reportes
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/reportes/balance-general">Balance General</a></li>
                            <li><a class="dropdown-item" href="/reportes/estado-resultados">Estado de Resultados</a></li>
                            <li><a class="dropdown-item" href="/reportes/flujo-efectivo">Flujo de Efectivo</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/ple"><i class="bi bi-file-earmark-text"></i> PLE</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarConfig" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear"></i> Configuración
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/empresas">Empresas</a></li>
                            <li><a class="dropdown-item" href="/periodos">Períodos Contables</a></li>
                            <li><a class="dropdown-item" href="/usuarios">Usuarios</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUser" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <?= $_SESSION['user']['nombre'] ?? 'Usuario' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash'])): ?>
        <?php $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
        <div class="container mt-3">
            <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <?= $content ?>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5 py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">Sistema de Contabilidad Perú &copy; <?= date('Y') ?> - v1.0.0</span>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/js/app.js"></script>
</body>
</html>
