<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistema de Contabilidad' ?> - Sistema de Contabilidad Perú</title>

    <!-- Tabler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="theme-light">
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md d-print-none navbar-dark">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="/dashboard">
                        <i class="ti ti-calculator"></i>
                        Sistema Contable Perú
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <?php if (isset($_SESSION['empresa_nombre'])): ?>
                        <div class="nav-item d-none d-md-flex me-3">
                            <div class="btn-list">
                                <a href="/empresas" class="btn btn-outline-light">
                                    <i class="ti ti-building me-1"></i>
                                    <?= htmlspecialchars($_SESSION['empresa_nombre']) ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown">
                            <span class="avatar avatar-sm" style="background-image: url(https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['nombre'] ?? 'U') ?>&background=0D8ABC&color=fff)"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div><?= $_SESSION['user']['nombre'] ?? 'Usuario' ?> <?= $_SESSION['user']['apellido'] ?? '' ?></div>
                                <div class="mt-1 small text-muted"><?= ucfirst($_SESSION['user']['rol'] ?? 'usuario') ?></div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="/logout" class="dropdown-item">
                                <i class="ti ti-logout me-2"></i>Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <header class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar navbar-light">
                    <div class="container-xl">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="/dashboard">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-home"></i>
                                    </span>
                                    <span class="nav-link-title">Inicio</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-book"></i>
                                    </span>
                                    <span class="nav-link-title">Contabilidad</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/asientos">Asientos Contables</a>
                                    <a class="dropdown-item" href="/plan-contable">Plan Contable</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/libros/diario">Libro Diario</a>
                                    <a class="dropdown-item" href="/libros/mayor">Libro Mayor</a>
                                    <a class="dropdown-item" href="/libros/caja-bancos">Caja y Bancos</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/terceros">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-users"></i>
                                    </span>
                                    <span class="nav-link-title">Terceros</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-cash"></i>
                                    </span>
                                    <span class="nav-link-title">Finanzas</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/cuentas-por-cobrar">
                                        <i class="ti ti-coins me-2"></i>Cuentas por Cobrar
                                    </a>
                                    <a class="dropdown-item" href="/cuentas-por-pagar">
                                        <i class="ti ti-receipt me-2"></i>Cuentas por Pagar
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/bancos">
                                        <i class="ti ti-building-bank me-2"></i>Caja y Bancos
                                    </a>
                                    <a class="dropdown-item" href="/movimientos-caja">
                                        <i class="ti ti-arrows-exchange me-2"></i>Movimientos de Caja
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/tipos-cambio">
                                        <i class="ti ti-currency-dollar me-2"></i>Tipos de Cambio
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-file-invoice"></i>
                                    </span>
                                    <span class="nav-link-title">Registros SUNAT</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/registro-ventas">Registro de Ventas</a>
                                    <a class="dropdown-item" href="/registro-compras">Registro de Compras</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/comprobantes">Comprobantes de Pago</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-chart-bar"></i>
                                    </span>
                                    <span class="nav-link-title">Reportes</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/reportes/balance-general">Balance General</a>
                                    <a class="dropdown-item" href="/reportes/estado-resultados">Estado de Resultados</a>
                                    <a class="dropdown-item" href="/reportes/flujo-efectivo">Flujo de Efectivo</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/ple">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-file-text"></i>
                                    </span>
                                    <span class="nav-link-title">PLE</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-settings"></i>
                                    </span>
                                    <span class="nav-link-title">Configuración</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/empresas">Empresas</a>
                                    <a class="dropdown-item" href="/periodos">Períodos Contables</a>
                                    <a class="dropdown-item" href="/usuarios">Usuarios</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="page-wrapper">
            <?php if (isset($_SESSION['flash'])): ?>
                <?php $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
                <div class="container-xl mt-3">
                    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                <i class="ti ti-<?= $flash['type'] === 'success' ? 'check' : 'alert-circle' ?> me-2"></i>
                            </div>
                            <div>
                                <?= htmlspecialchars($flash['message']) ?>
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <div class="page-body">
                <div class="container-xl">
                    <?= $content ?>
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Sistema de Contabilidad Perú &copy; <?= date('Y') ?>
                                </li>
                                <li class="list-inline-item">
                                    Versión 1.0.0
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Tabler Core -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <!-- Custom JS -->
    <script src="/js/app.js"></script>
</body>
</html>
