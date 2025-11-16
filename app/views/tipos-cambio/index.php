<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-currency-dollar me-2"></i>Tipos de Cambio
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="/tipos-cambio/promedio-mensual" class="btn btn-outline-info">
                        <i class="ti ti-chart-line me-1"></i>
                        Promedio Mensual
                    </a>
                    <a href="/tipos-cambio/cierre" class="btn btn-outline-warning">
                        <i class="ti ti-calendar-time me-1"></i>
                        Tipo Cambio Cierre
                    </a>
                    <a href="/tipos-cambio/nuevo" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>
                        Registrar Tipo de Cambio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Tipo de cambio actual -->
        <?php if ($tipo_cambio_actual): ?>
        <div class="card mb-3 border-primary">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="card-title text-primary">
                            <i class="ti ti-info-circle me-2"></i>Tipo de Cambio Actual (<?= htmlspecialchars($tipo_cambio_actual['moneda']) ?>)
                        </h3>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="text-muted">Fecha</div>
                        <div class="h3"><?= date('d/m/Y', strtotime($tipo_cambio_actual['fecha'])) ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted">Compra</div>
                        <div class="h3 text-success">S/ <?= number_format($tipo_cambio_actual['compra'], 4) ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted">Venta</div>
                        <div class="h3 text-danger">S/ <?= number_format($tipo_cambio_actual['venta'], 4) ?></div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-muted">Fuente</div>
                        <div class="h4">
                            <span class="badge badge-info"><?= htmlspecialchars($tipo_cambio_actual['fuente']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="/tipos-cambio">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label">Moneda</label>
                            <select name="moneda" class="form-select">
                                <option value="USD" <?= ($moneda ?? 'USD') === 'USD' ? 'selected' : '' ?>>USD - Dólar Americano</option>
                                <option value="EUR" <?= ($moneda ?? 'USD') === 'EUR' ? 'selected' : '' ?>>EUR - Euro</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Desde</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="<?= $fecha_inicio ?? date('Y-m-01') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Hasta</label>
                            <input type="date" name="fecha_fin" class="form-control" value="<?= $fecha_fin ?? date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-filter me-1"></i>Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de tipos de cambio -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Historial de Tipos de Cambio</h3>
            </div>
            <div class="card-body">
                <?php if (empty($tipos_cambio)): ?>
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-currency-dollar"></i>
                        </div>
                        <p class="empty-title">No hay tipos de cambio registrados</p>
                        <p class="empty-subtitle text-muted">
                            No se encontraron tipos de cambio en el período seleccionado
                        </p>
                        <div class="empty-action">
                            <a href="/tipos-cambio/nuevo" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>
                                Registrar Primer Tipo de Cambio
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Moneda</th>
                                    <th class="text-end">Compra</th>
                                    <th class="text-end">Venta</th>
                                    <th class="text-end">Promedio</th>
                                    <th>Fuente</th>
                                    <th>Registrado</th>
                                    <th class="w-1">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tipos_cambio as $tc): ?>
                                    <tr>
                                        <td>
                                            <strong><?= date('d/m/Y', strtotime($tc['fecha'])) ?></strong>
                                            <div class="text-muted small"><?= date('l', strtotime($tc['fecha'])) ?></div>
                                        </td>
                                        <td>
                                            <span class="badge badge-outline text-primary">
                                                <?= htmlspecialchars($tc['moneda']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-success">
                                                S/ <?= number_format($tc['compra'], 4) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-danger">
                                                S/ <?= number_format($tc['venta'], 4) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <strong>S/ <?= number_format(($tc['compra'] + $tc['venta']) / 2, 4) ?></strong>
                                        </td>
                                        <td>
                                            <?php
                                            $badge_fuente = [
                                                'SUNAT' => 'badge-primary',
                                                'SBS' => 'badge-info',
                                                'Manual' => 'badge-secondary',
                                                'Banco' => 'badge-success'
                                            ];
                                            ?>
                                            <span class="badge <?= $badge_fuente[$tc['fuente']] ?? 'badge-secondary' ?>">
                                                <?= htmlspecialchars($tc['fuente']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($tc['created_at'])) ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <button type="button" class="btn btn-sm btn-icon btn-ghost-danger"
                                                        onclick="eliminarTipoCambio(<?= $tc['id'] ?>)"
                                                        title="Eliminar">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function eliminarTipoCambio(id) {
    if (confirm('¿Está seguro que desea eliminar este tipo de cambio?')) {
        fetch('/tipos-cambio/eliminar/' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al eliminar: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error al eliminar el tipo de cambio');
            console.error('Error:', error);
        });
    }
}
</script>
