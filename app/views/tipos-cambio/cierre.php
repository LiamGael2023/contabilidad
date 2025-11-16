<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-calendar-time me-2"></i>Tipo de Cambio de Cierre
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/tipos-cambio" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="/tipos-cambio/cierre">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Año</label>
                            <select name="anio" class="form-select">
                                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                    <option value="<?= $y ?>" <?= ($anio ?? date('Y')) == $y ? 'selected' : '' ?>>
                                        <?= $y ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mes</label>
                            <select name="mes" class="form-select">
                                <?php
                                $meses = [
                                    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                                ];
                                foreach ($meses as $num => $nombre):
                                ?>
                                    <option value="<?= $num ?>" <?= ($mes ?? date('m')) == $num ? 'selected' : '' ?>>
                                        <?= $nombre ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Moneda</label>
                            <select name="moneda" class="form-select">
                                <option value="USD" <?= ($moneda ?? 'USD') === 'USD' ? 'selected' : '' ?>>USD</option>
                                <option value="EUR" <?= ($moneda ?? 'USD') === 'EUR' ? 'selected' : '' ?>>EUR</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Tipo de Cambio de Cierre - <?= $meses[$mes] ?? 'Mes' ?> <?= $anio ?>
                </h3>
            </div>
            <div class="card-body">
                <?php if (!$tipo_cambio): ?>
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-alert-triangle"></i>
                        </div>
                        <p class="empty-title">No hay tipo de cambio de cierre</p>
                        <p class="empty-subtitle text-muted">
                            No se encontró tipo de cambio para el último día del mes seleccionado
                        </p>
                        <div class="empty-action">
                            <a href="/tipos-cambio/nuevo" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>
                                Registrar Tipo de Cambio
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-primary alert-important">
                        <div class="d-flex">
                            <div>
                                <i class="ti ti-info-circle icon me-2"></i>
                            </div>
                            <div>
                                <h4 class="alert-title">Tipo de Cambio para Cierre de Mes</h4>
                                <div class="text-muted">
                                    Este es el tipo de cambio que debe utilizarse para las diferencias de cambio
                                    y ajustes al cierre del período <?= $meses[$mes] ?? '' ?> <?= $anio ?>.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-success-lt">
                                <div class="card-body">
                                    <div class="text-muted">Fecha de Cierre</div>
                                    <div class="h2 mb-0"><?= date('d/m/Y', strtotime($tipo_cambio['fecha'])) ?></div>
                                    <div class="text-muted small">
                                        Último día del mes
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary-lt">
                                <div class="card-body">
                                    <div class="text-muted">Tipo Cambio Compra</div>
                                    <div class="h1 mb-0">S/ <?= number_format($tipo_cambio['compra'], 4) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger-lt">
                                <div class="card-body">
                                    <div class="text-muted">Tipo Cambio Venta</div>
                                    <div class="h1 mb-0">S/ <?= number_format($tipo_cambio['venta'], 4) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <h4 class="alert-title">Información Adicional</h4>
                        <ul class="mb-0">
                            <li><strong>Moneda:</strong> <?= htmlspecialchars($tipo_cambio['moneda']) ?></li>
                            <li><strong>Fuente:</strong> <?= htmlspecialchars($tipo_cambio['fuente']) ?></li>
                            <li><strong>Promedio:</strong> S/ <?= number_format(($tipo_cambio['compra'] + $tipo_cambio['venta']) / 2, 4) ?></li>
                            <li><strong>Registrado:</strong> <?= date('d/m/Y H:i', strtotime($tipo_cambio['created_at'])) ?></li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h4 class="alert-title">
                            <i class="ti ti-alert-triangle me-2"></i>Importante
                        </h4>
                        <p class="mb-0">
                            Este tipo de cambio debe utilizarse para:
                        </p>
                        <ul class="mb-0">
                            <li>Convertir saldos en moneda extranjera al cierre del mes</li>
                            <li>Calcular diferencias de cambio</li>
                            <li>Ajustes contables de fin de período</li>
                            <li>Estados financieros del período</li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
