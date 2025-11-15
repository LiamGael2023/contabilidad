<div class="row no-print">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-graph-up"></i> Balance General</h1>
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
                        Al <?= date('d', strtotime($periodo['fecha_fin'])) ?> de
                        <?= ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                             'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'][$periodo['mes']] ?>
                        de <?= $periodo['anio'] ?>
                    </strong>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- ACTIVO -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">ACTIVO</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($balance['activos'])): ?>
                    <table class="table table-sm">
                        <tbody>
                            <?php foreach ($balance['activos'] as $cuenta): ?>
                                <?php if ($cuenta['saldo'] != 0): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($cuenta['codigo']) ?></td>
                                        <td><?= htmlspecialchars($cuenta['descripcion']) ?></td>
                                        <td class="text-end"><?= number_format($cuenta['saldo'], 2) ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="fw-bold balance-total">
                            <tr>
                                <td colspan="2">TOTAL ACTIVO</td>
                                <td class="text-end">S/ <?= number_format($balance['total_activo'], 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No hay cuentas de activo con saldo.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- PASIVO Y PATRIMONIO -->
    <div class="col-md-6">
        <!-- PASIVO -->
        <div class="card mb-3">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">PASIVO</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($balance['pasivos'])): ?>
                    <table class="table table-sm">
                        <tbody>
                            <?php foreach ($balance['pasivos'] as $cuenta): ?>
                                <?php if ($cuenta['saldo'] != 0): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($cuenta['codigo']) ?></td>
                                        <td><?= htmlspecialchars($cuenta['descripcion']) ?></td>
                                        <td class="text-end"><?= number_format($cuenta['saldo'], 2) ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="fw-bold">
                            <tr>
                                <td colspan="2">TOTAL PASIVO</td>
                                <td class="text-end">S/ <?= number_format($balance['total_pasivo'], 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No hay cuentas de pasivo con saldo.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- PATRIMONIO -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">PATRIMONIO</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($balance['patrimonio'])): ?>
                    <table class="table table-sm">
                        <tbody>
                            <?php foreach ($balance['patrimonio'] as $cuenta): ?>
                                <?php if ($cuenta['saldo'] != 0): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($cuenta['codigo']) ?></td>
                                        <td><?= htmlspecialchars($cuenta['descripcion']) ?></td>
                                        <td class="text-end"><?= number_format($cuenta['saldo'], 2) ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="fw-bold">
                            <tr>
                                <td colspan="2">TOTAL PATRIMONIO</td>
                                <td class="text-end">S/ <?= number_format($balance['total_patrimonio'], 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No hay cuentas de patrimonio con saldo.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- TOTAL PASIVO + PATRIMONIO -->
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <div class="d-flex justify-content-between fw-bold balance-total">
                    <span>TOTAL PASIVO + PATRIMONIO</span>
                    <span>S/ <?= number_format($balance['total_pasivo_patrimonio'], 2) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$diferencia = abs($balance['total_activo'] - $balance['total_pasivo_patrimonio']);
if ($diferencia > 0.01): ?>
    <div class="alert alert-warning mt-3 no-print">
        <i class="bi bi-exclamation-triangle"></i>
        <strong>Advertencia:</strong> El balance no está cuadrado.
        Diferencia: S/ <?= number_format($diferencia, 2) ?>
    </div>
<?php endif; ?>
