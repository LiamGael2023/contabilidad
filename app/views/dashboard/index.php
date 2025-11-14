<div class="row">
    <div class="col-12">
        <h1><i class="bi bi-house"></i> Dashboard</h1>
        <hr>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-building"></i> Empresas</h5>
                <h2><?= $stats['total_empresas'] ?></h2>
                <p class="card-text">Empresas registradas</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-journal-text"></i> Asientos del Mes</h5>
                <h2><?= $stats['asientos_mes'] ?></h2>
                <p class="card-text">Asientos registrados</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-receipt"></i> Comprobantes del Mes</h5>
                <h2><?= $stats['comprobantes_mes'] ?></h2>
                <p class="card-text">Comprobantes emitidos</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5><i class="bi bi-bookmark-check"></i> Acceso Rápido</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="/asientos/nuevo" class="list-group-item list-group-item-action">
                        <i class="bi bi-plus-circle"></i> Nuevo Asiento Contable
                    </a>
                    <a href="/comprobantes/nuevo" class="list-group-item list-group-item-action">
                        <i class="bi bi-receipt"></i> Nuevo Comprobante
                    </a>
                    <a href="/libros/diario" class="list-group-item list-group-item-action">
                        <i class="bi bi-journal-text"></i> Ver Libro Diario
                    </a>
                    <a href="/reportes/balance-general" class="list-group-item list-group-item-action">
                        <i class="bi bi-graph-up"></i> Balance General
                    </a>
                    <a href="/ple" class="list-group-item list-group-item-action">
                        <i class="bi bi-file-earmark-text"></i> Generar PLE
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5><i class="bi bi-info-circle"></i> Información del Sistema</h5>
            </div>
            <div class="card-body">
                <p><strong>Usuario:</strong> <?= $user['nombre'] . ' ' . $user['apellido'] ?></p>
                <p><strong>Rol:</strong> <?= ucfirst($user['rol']) ?></p>
                <p><strong>Fecha:</strong> <?= date('d/m/Y') ?></p>
                <p><strong>Versión:</strong> 1.0.0</p>

                <hr>

                <h6>Empresas Activas:</h6>
                <ul class="list-unstyled">
                    <?php foreach ($empresas as $empresa): ?>
                        <li>
                            <i class="bi bi-building"></i>
                            <?= htmlspecialchars($empresa['razon_social']) ?>
                            <small class="text-muted">(<?= $empresa['ruc'] ?>)</small>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if (empty($empresas)): ?>
                    <div class="alert alert-warning">
                        No hay empresas registradas.
                        <a href="/empresas/nueva" class="alert-link">Crear nueva empresa</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
