<div class="row no-print">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-graph-up"></i> Estado de Resultados</h1>
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
                        Del 01 al <?= date('d', strtotime($periodo['fecha_fin'])) ?> de
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
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">ESTADO DE RESULTADOS POR NATURALEZA</h5>
            </div>
            <div class="card-body">
                <!-- INGRESOS -->
                <h6 class="bg-success text-white p-2">INGRESOS</h6>
                <?php if (!empty($estado['ingresos'])): ?>
                    <table class="table table-sm mb-3">
                        <tbody>
                            <?php foreach ($estado['ingresos'] as $cuenta): ?>
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
                                <td colspan="2">TOTAL INGRESOS</td>
                                <td class="text-end">S/ <?= number_format($estado['total_ingresos'], 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                <?php else: ?>
                    <p class="text-muted mb-3">No hay ingresos registrados.</p>
                <?php endif; ?>

                <!-- GASTOS -->
                <h6 class="bg-danger text-white p-2">GASTOS</h6>
                <?php if (!empty($estado['gastos'])): ?>
                    <table class="table table-sm mb-3">
                        <tbody>
                            <?php foreach ($estado['gastos'] as $cuenta): ?>
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
                                <td colspan="2">TOTAL GASTOS</td>
                                <td class="text-end">S/ <?= number_format($estado['total_gastos'], 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                <?php else: ?>
                    <p class="text-muted mb-3">No hay gastos registrados.</p>
                <?php endif; ?>

                <hr>

                <!-- RESULTADO -->
                <div class="balance-total">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr class="fw-bold fs-5">
                                <td colspan="2">
                                    <?= $estado['utilidad_neta'] >= 0 ? 'UTILIDAD DEL EJERCICIO' : 'PÉRDIDA DEL EJERCICIO' ?>
                                </td>
                                <td class="text-end text-<?= $estado['utilidad_neta'] >= 0 ? 'success' : 'danger' ?>">
                                    S/ <?= number_format(abs($estado['utilidad_neta']), 2) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
