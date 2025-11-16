<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-chart-bar me-2"></i>Antigüedad de Saldos
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/cuentas-por-cobrar" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Reporte de Antigüedad de Saldos por Cliente</h3>
            </div>
            <div class="card-body">
                <?php if (empty($antiguedad)): ?>
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-check"></i>
                        </div>
                        <p class="empty-title">No hay saldos pendientes</p>
                        <p class="empty-subtitle text-muted">
                            Todas las cuentas están pagadas
                        </p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-sm">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th class="text-end">Vigente<br><small class="text-muted">(No vencido)</small></th>
                                    <th class="text-end">1-30 días<br><small class="text-muted">(Vencido)</small></th>
                                    <th class="text-end">31-60 días<br><small class="text-muted">(Vencido)</small></th>
                                    <th class="text-end">61-90 días<br><small class="text-muted">(Vencido)</small></th>
                                    <th class="text-end">+90 días<br><small class="text-muted">(Vencido)</small></th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($antiguedad as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['cliente']) ?></td>
                                        <td class="text-end">
                                            <?= $item['vigente'] > 0 ? 'S/ ' . number_format($item['vigente'], 2) : '-' ?>
                                        </td>
                                        <td class="text-end <?= $item['vencido_30'] > 0 ? 'table-warning' : '' ?>">
                                            <?= $item['vencido_30'] > 0 ? 'S/ ' . number_format($item['vencido_30'], 2) : '-' ?>
                                        </td>
                                        <td class="text-end <?= $item['vencido_60'] > 0 ? 'table-warning' : '' ?>">
                                            <?= $item['vencido_60'] > 0 ? 'S/ ' . number_format($item['vencido_60'], 2) : '-' ?>
                                        </td>
                                        <td class="text-end <?= $item['vencido_90'] > 0 ? 'table-danger' : '' ?>">
                                            <?= $item['vencido_90'] > 0 ? 'S/ ' . number_format($item['vencido_90'], 2) : '-' ?>
                                        </td>
                                        <td class="text-end <?= $item['vencido_mas_90'] > 0 ? 'table-danger' : '' ?>">
                                            <?= $item['vencido_mas_90'] > 0 ? 'S/ ' . number_format($item['vencido_mas_90'], 2) : '-' ?>
                                        </td>
                                        <td class="text-end">
                                            <strong>S/ <?= number_format($item['total'], 2) ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td>TOTALES:</td>
                                    <td class="text-end">
                                        S/ <?= number_format(array_sum(array_column($antiguedad, 'vigente')), 2) ?>
                                    </td>
                                    <td class="text-end table-warning">
                                        S/ <?= number_format(array_sum(array_column($antiguedad, 'vencido_30')), 2) ?>
                                    </td>
                                    <td class="text-end table-warning">
                                        S/ <?= number_format(array_sum(array_column($antiguedad, 'vencido_60')), 2) ?>
                                    </td>
                                    <td class="text-end table-danger">
                                        S/ <?= number_format(array_sum(array_column($antiguedad, 'vencido_90')), 2) ?>
                                    </td>
                                    <td class="text-end table-danger">
                                        S/ <?= number_format(array_sum(array_column($antiguedad, 'vencido_mas_90')), 2) ?>
                                    </td>
                                    <td class="text-end">
                                        S/ <?= number_format(array_sum(array_column($antiguedad, 'total')), 2) ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <strong>Leyenda:</strong>
                                    <ul class="mb-0">
                                        <li><strong>Vigente:</strong> Cuentas que aún no han vencido</li>
                                        <li><strong>1-30 días:</strong> Vencidas hace menos de 30 días</li>
                                        <li><strong>31-60 días:</strong> Vencidas entre 31 y 60 días</li>
                                        <li><strong>61-90 días:</strong> Vencidas entre 61 y 90 días</li>
                                        <li><strong>+90 días:</strong> Vencidas hace más de 90 días (requieren atención urgente)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
