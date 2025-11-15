<div class="row no-print">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-cash-stack"></i> Libro Caja y Bancos</h1>
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="bi bi-printer"></i> Imprimir
            </button>
        </div>
        <hr>
    </div>
</div>

<div class="row mb-3 no-print">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6>Período</h6>
                <p class="mb-0">
                    <strong>
                        <?= sprintf('%02d', $periodo['mes']) ?>/<?= $periodo['anio'] ?>
                    </strong>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($cuentas)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No hay cuentas de efectivo registradas.
                    </div>
                <?php else: ?>
                    <?php foreach ($cuentas as $cuenta): ?>
                        <div class="mb-4">
                            <h5>
                                <code><?= htmlspecialchars($cuenta['codigo']) ?></code>
                                <?= htmlspecialchars($cuenta['descripcion']) ?>
                            </h5>

                            <?php if (empty($cuenta['movimientos'])): ?>
                                <p class="text-muted">No hay movimientos en esta cuenta.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Asiento</th>
                                                <th>Glosa</th>
                                                <th class="text-end">Ingresos</th>
                                                <th class="text-end">Egresos</th>
                                                <th class="text-end">Saldo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $saldo = 0;
                                            foreach ($cuenta['movimientos'] as $mov):
                                                $saldo += ($mov['debe'] - $mov['haber']);
                                            ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($mov['fecha'])) ?></td>
                                                    <td><?= str_pad($mov['numero_asiento'], 6, '0', STR_PAD_LEFT) ?></td>
                                                    <td><?= htmlspecialchars($mov['glosa'] ?: $mov['glosa_asiento']) ?></td>
                                                    <td class="text-end">
                                                        <?= $mov['debe'] > 0 ? number_format($mov['debe'], 2) : '-' ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <?= $mov['haber'] > 0 ? number_format($mov['haber'], 2) : '-' ?>
                                                    </td>
                                                    <td class="text-end fw-bold">
                                                        <?= number_format($saldo, 2) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot class="fw-bold">
                                            <tr>
                                                <td colspan="3" class="text-end">TOTALES:</td>
                                                <td class="text-end">
                                                    <?= number_format(array_sum(array_column($cuenta['movimientos'], 'debe')), 2) ?>
                                                </td>
                                                <td class="text-end">
                                                    <?= number_format(array_sum(array_column($cuenta['movimientos'], 'haber')), 2) ?>
                                                </td>
                                                <td class="text-end"><?= number_format($saldo, 2) ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
