<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-receipt-cutoff"></i> Registro de Ventas</h1>
            <div>
                <a href="/registro-ventas/exportar-ple" class="btn btn-success">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Exportar PLE 14.1
                </a>
                <a href="/registro-ventas/nuevo" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Registrar Venta
                </a>
            </div>
        </div>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-muted">Período</div>
                        <div class="h3"><?= htmlspecialchars($periodo['mes']) ?>/<?= htmlspecialchars($periodo['anio']) ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted">Base Imponible</div>
                        <div class="h3">S/ <?= number_format($totales['total_base'] ?? 0, 2) ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted">IGV</div>
                        <div class="h3">S/ <?= number_format($totales['total_igv'] ?? 0, 2) ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted">Total</div>
                        <div class="h3 text-primary">S/ <?= number_format($totales['total_general'] ?? 0, 2) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($ventas)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No hay ventas registradas para este período.
                        <a href="/registro-ventas/nuevo" class="alert-link">Registrar la primera venta</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Serie-Número</th>
                                    <th>Cliente</th>
                                    <th>Base</th>
                                    <th>IGV</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ventas as $venta): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($venta['fecha_emision'])) ?></td>
                                        <td>
                                            <?php
                                            $tipos = ['01' => 'Factura', '03' => 'Boleta', '07' => 'N/C', '08' => 'N/D'];
                                            echo $tipos[$venta['tipo_comprobante']] ?? $venta['tipo_comprobante'];
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($venta['serie'] . '-' . $venta['numero']) ?></td>
                                        <td>
                                            <small>
                                                <?= htmlspecialchars($venta['razon_social_cliente']) ?><br>
                                                <span class="text-muted"><?= htmlspecialchars($venta['numero_documento_cliente']) ?></span>
                                            </small>
                                        </td>
                                        <td class="text-end"><?= number_format($venta['base_imponible'], 2) ?></td>
                                        <td class="text-end"><?= number_format($venta['igv'], 2) ?></td>
                                        <td class="text-end"><strong><?= number_format($venta['importe_total'], 2) ?></strong></td>
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
