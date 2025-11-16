<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-chart-line me-2"></i>Promedio Mensual de Tipo de Cambio
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
                <form method="GET" action="/tipos-cambio/promedio-mensual">
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
                    Promedio Mensual - <?= $meses[$mes] ?? 'Mes' ?> <?= $anio ?>
                </h3>
            </div>
            <div class="card-body">
                <?php if (!$promedio): ?>
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-info-circle"></i>
                        </div>
                        <p class="empty-title">No hay datos disponibles</p>
                        <p class="empty-subtitle text-muted">
                            No se encontraron tipos de cambio para el período seleccionado
                        </p>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary-lt">
                                <div class="card-body">
                                    <div class="text-muted">Promedio Compra</div>
                                    <div class="h1 mb-0">S/ <?= number_format($promedio['promedio_compra'], 4) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger-lt">
                                <div class="card-body">
                                    <div class="text-muted">Promedio Venta</div>
                                    <div class="h1 mb-0">S/ <?= number_format($promedio['promedio_venta'], 4) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success-lt">
                                <div class="card-body">
                                    <div class="text-muted">Promedio General</div>
                                    <div class="h1 mb-0">
                                        S/ <?= number_format(($promedio['promedio_compra'] + $promedio['promedio_venta']) / 2, 4) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Información del Período:</strong><br>
                        Registros encontrados: <?= $promedio['cantidad_registros'] ?? 0 ?><br>
                        Mínimo Compra: S/ <?= number_format($promedio['minimo_compra'] ?? 0, 4) ?><br>
                        Máximo Compra: S/ <?= number_format($promedio['maximo_compra'] ?? 0, 4) ?><br>
                        Mínimo Venta: S/ <?= number_format($promedio['minimo_venta'] ?? 0, 4) ?><br>
                        Máximo Venta: S/ <?= number_format($promedio['maximo_venta'] ?? 0, 4) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
