<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-chart-line me-2"></i>Resumen de Liquidez
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/bancos" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <?php foreach ($resumen as $item): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total en <?= htmlspecialchars($item['moneda']) ?></div>
                            </div>
                            <div class="h1 mb-3 <?= $item['moneda'] === 'PEN' ? 'text-primary' : 'text-success' ?>">
                                <?= $item['moneda'] === 'PEN' ? 'S/' : ($item['moneda'] === 'USD' ? '$' : '€') ?>
                                <?= number_format($item['saldo_total'], 2) ?>
                            </div>
                            <div class="d-flex mb-2">
                                <div>
                                    <strong><?= $item['cantidad_cuentas'] ?></strong> cuenta(s) activa(s)<br>
                                    <small class="text-muted">
                                        Saldo inicial: <?= $item['moneda'] === 'PEN' ? 'S/' : ($item['moneda'] === 'USD' ? '$' : '€') ?>
                                        <?= number_format($item['saldo_inicial_total'], 2) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($resumen)): ?>
                <div class="col-12">
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-building-bank"></i>
                        </div>
                        <p class="empty-title">No hay cuentas activas</p>
                        <p class="empty-subtitle text-muted">
                            No se encontraron cuentas bancarias o cajas activas
                        </p>
                        <div class="empty-action">
                            <a href="/bancos/nuevo" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>
                                Registrar Primera Cuenta
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($resumen)): ?>
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Análisis de Liquidez</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>Moneda</th>
                                <th class="text-end">Cantidad de Cuentas</th>
                                <th class="text-end">Saldo Inicial</th>
                                <th class="text-end">Saldo Actual</th>
                                <th class="text-end">Variación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resumen as $item): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-outline <?= $item['moneda'] === 'PEN' ? 'text-primary' : 'text-success' ?>">
                                            <?= htmlspecialchars($item['moneda']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end"><?= $item['cantidad_cuentas'] ?></td>
                                    <td class="text-end">
                                        <?= $item['moneda'] === 'PEN' ? 'S/' : ($item['moneda'] === 'USD' ? '$' : '€') ?>
                                        <?= number_format($item['saldo_inicial_total'], 2) ?>
                                    </td>
                                    <td class="text-end">
                                        <strong>
                                            <?= $item['moneda'] === 'PEN' ? 'S/' : ($item['moneda'] === 'USD' ? '$' : '€') ?>
                                            <?= number_format($item['saldo_total'], 2) ?>
                                        </strong>
                                    </td>
                                    <td class="text-end">
                                        <?php
                                        $variacion = $item['saldo_total'] - $item['saldo_inicial_total'];
                                        $porcentaje = $item['saldo_inicial_total'] > 0
                                            ? ($variacion / $item['saldo_inicial_total']) * 100
                                            : 0;
                                        ?>
                                        <span class="<?= $variacion >= 0 ? 'text-success' : 'text-danger' ?>">
                                            <?= $variacion >= 0 ? '+' : '' ?><?= number_format($variacion, 2) ?>
                                            (<?= $variacion >= 0 ? '+' : '' ?><?= number_format($porcentaje, 1) ?>%)
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
