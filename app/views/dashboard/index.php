<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Bienvenido
                </div>
                <h2 class="page-title">
                    <i class="ti ti-home me-2"></i>
                    Dashboard
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Tarjetas de Estadísticas -->
        <div class="row row-deck row-cards">
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Empresas</div>
                        </div>
                        <div class="h1 mb-3"><?= $stats['total_empresas'] ?></div>
                        <div class="d-flex mb-2">
                            <div>Empresas registradas</div>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-primary" style="width: 100%" role="progressbar">
                                <span class="visually-hidden">100% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Asientos del Mes</div>
                        </div>
                        <div class="h1 mb-3"><?= $stats['asientos_mes'] ?></div>
                        <div class="d-flex mb-2">
                            <div>Asientos contables registrados</div>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width: 75%" role="progressbar">
                                <span class="visually-hidden">75% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Comprobantes del Mes</div>
                        </div>
                        <div class="h1 mb-3"><?= $stats['comprobantes_mes'] ?></div>
                        <div class="d-flex mb-2">
                            <div>Comprobantes emitidos</div>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-info" style="width: 60%" role="progressbar">
                                <span class="visually-hidden">60% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-deck row-cards mt-4">
            <!-- Acceso Rápido -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-bolt me-2"></i>
                            Acceso Rápido
                        </h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="/asientos/nuevo" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar rounded" style="background-color: #0D8ABC">
                                    <i class="ti ti-plus"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="font-weight-medium">Nuevo Asiento Contable</div>
                                <div class="text-muted">Registrar un nuevo asiento en partida doble</div>
                            </div>
                        </a>
                        <a href="/comprobantes/nuevo" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar rounded" style="background-color: #198754">
                                    <i class="ti ti-receipt"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="font-weight-medium">Nuevo Comprobante</div>
                                <div class="text-muted">Registrar factura, boleta o nota</div>
                            </div>
                        </a>
                        <a href="/libros/diario" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar rounded" style="background-color: #FFC107">
                                    <i class="ti ti-book"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="font-weight-medium">Ver Libro Diario</div>
                                <div class="text-muted">Consultar movimientos contables</div>
                            </div>
                        </a>
                        <a href="/reportes/balance-general" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar rounded" style="background-color: #DC3545">
                                    <i class="ti ti-chart-bar"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="font-weight-medium">Balance General</div>
                                <div class="text-muted">Ver estado de situación financiera</div>
                            </div>
                        </a>
                        <a href="/ple" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar rounded" style="background-color: #6C757D">
                                    <i class="ti ti-file-text"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="font-weight-medium">Generar PLE</div>
                                <div class="text-muted">Programa de Libros Electrónicos para SUNAT</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-info-circle me-2"></i>
                            Información del Sistema
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Usuario</div>
                                <div class="datagrid-content"><?= $user['nombre'] . ' ' . $user['apellido'] ?></div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Rol</div>
                                <div class="datagrid-content">
                                    <span class="badge bg-primary"><?= ucfirst($user['rol']) ?></span>
                                </div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Fecha Actual</div>
                                <div class="datagrid-content"><?= date('d/m/Y H:i') ?></div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Versión del Sistema</div>
                                <div class="datagrid-content">1.0.0</div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="card-title">Empresas Activas</div>
                        <?php if (!empty($empresas)): ?>
                            <div class="list-group list-group-flush list-group-hoverable">
                                <?php foreach ($empresas as $empresa): ?>
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar">
                                                    <i class="ti ti-building"></i>
                                                </span>
                                            </div>
                                            <div class="col text-truncate">
                                                <div class="text-reset d-block"><?= htmlspecialchars($empresa['razon_social']) ?></div>
                                                <div class="d-block text-muted text-truncate mt-n1">
                                                    RUC: <?= $empresa['ruc'] ?>
                                                </div>
                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty">
                                <div class="empty-icon">
                                    <i class="ti ti-building"></i>
                                </div>
                                <p class="empty-title">No hay empresas registradas</p>
                                <p class="empty-subtitle text-muted">
                                    Comienza creando tu primera empresa
                                </p>
                                <div class="empty-action">
                                    <a href="/empresas/nueva" class="btn btn-primary">
                                        <i class="ti ti-plus me-2"></i>
                                        Nueva Empresa
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
