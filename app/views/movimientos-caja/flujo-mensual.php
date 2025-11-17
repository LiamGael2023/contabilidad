<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-calendar-month me-2"></i>Flujo de Caja Mensual
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/movimientos-caja" class="btn btn-outline-secondary">
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
                <form method="GET" action="/movimientos-caja/flujo-mensual">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label">Año</label>
                            <select name="anio" class="form-select">
                                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                    <option value="<?= $y ?>" <?= ($anio ?? date('Y')) == $y ? 'selected' : '' ?>>
                                        <?= $y ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search me-1"></i>Consultar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Flujo de Caja - <?= $meses[$mes ?? date('m')] ?? 'Mes' ?> <?= $anio ?? date('Y') ?>
                </h3>
            </div>
            <div class="card-body">
                <?php if (empty($flujo)): ?>
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-info-circle"></i>
                        </div>
                        <p class="empty-title">No hay movimientos</p>
                        <p class="empty-subtitle text-muted">
                            No se encontraron movimientos para el período seleccionado
                        </p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Día</th>
                                    <th class="text-end">Ingresos</th>
                                    <th class="text-end">Egresos</th>
                                    <th class="text-end">Saldo Neto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalIngresos = 0;
                                $totalEgresos = 0;
                                foreach ($flujo as $item):
                                    $totalIngresos += $item['ingresos'];
                                    $totalEgresos += $item['egresos'];
                                    $saldoDia = $item['ingresos'] - $item['egresos'];
                                ?>
                                    <tr>
                                        <td><?= $item['dia'] ?></td>
                                        <td class="text-end text-success">
                                            S/ <?= number_format($item['ingresos'], 2) ?>
                                        </td>
                                        <td class="text-end text-danger">
                                            S/ <?= number_format($item['egresos'], 2) ?>
                                        </td>
                                        <td class="text-end <?= $saldoDia >= 0 ? 'text-success' : 'text-danger' ?>">
                                            <strong>S/ <?= number_format($saldoDia, 2) ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td>TOTALES:</td>
                                    <td class="text-end text-success">
                                        S/ <?= number_format($totalIngresos, 2) ?>
                                    </td>
                                    <td class="text-end text-danger">
                                        S/ <?= number_format($totalEgresos, 2) ?>
                                    </td>
                                    <td class="text-end <?= ($totalIngresos - $totalEgresos) >= 0 ? 'text-success' : 'text-danger' ?>">
                                        S/ <?= number_format($totalIngresos - $totalEgresos, 2) ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
