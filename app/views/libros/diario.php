<div class="row no-print">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-journal-text"></i> Libro Diario</h1>
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
                <?php if (empty($asientos)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No hay movimientos registrados en este período.
                    </div>
                <?php else: ?>
                    <?php foreach ($asientos as $asiento): ?>
                        <div class="mb-4 libro-asiento">
                            <div class="row bg-light p-2 mb-2">
                                <div class="col-md-8">
                                    <strong>Asiento N° <?= str_pad($asiento['numero_asiento'], 6, '0', STR_PAD_LEFT) ?></strong>
                                    - <?= date('d/m/Y', strtotime($asiento['fecha'])) ?>
                                </div>
                                <div class="col-md-4 text-end">
                                    <?php if ($asiento['serie_comprobante']): ?>
                                        <small><?= $asiento['tipo_comprobante'] ?>
                                        <?= $asiento['serie_comprobante'] ?>-<?= $asiento['numero_comprobante'] ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="px-3">
                                <p><strong>Glosa:</strong> <?= htmlspecialchars($asiento['glosa']) ?></p>

                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 120px;">Cuenta</th>
                                            <th>Descripción</th>
                                            <th style="width: 150px;" class="text-end">Debe</th>
                                            <th style="width: 150px;" class="text-end">Haber</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($asiento['detalles'] as $detalle): ?>
                                            <tr>
                                                <td><code><?= htmlspecialchars($detalle['codigo']) ?></code></td>
                                                <td><?= htmlspecialchars($detalle['nombre_cuenta']) ?></td>
                                                <td class="text-end">
                                                    <?= $detalle['debe'] > 0 ? number_format($detalle['debe'], 2) : '' ?>
                                                </td>
                                                <td class="text-end">
                                                    <?= $detalle['haber'] > 0 ? number_format($detalle['haber'], 2) : '' ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="fw-bold">
                                        <tr>
                                            <td colspan="2" class="text-end">TOTALES:</td>
                                            <td class="text-end"><?= number_format($asiento['total_debe'], 2) ?></td>
                                            <td class="text-end"><?= number_format($asiento['total_haber'], 2) ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
