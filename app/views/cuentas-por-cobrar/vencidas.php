<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-alert-circle me-2"></i>Cuentas Vencidas
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
                <h3 class="card-title">Cuentas por Cobrar Vencidas</h3>
            </div>
            <div class="card-body">
                <?php if (empty($cuentas)): ?>
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-check"></i>
                        </div>
                        <p class="empty-title">No hay cuentas vencidas</p>
                        <p class="empty-subtitle text-muted">
                            Todas las cuentas están al día
                        </p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Documento</th>
                                    <th>Fecha Emisión</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Días Vencidos</th>
                                    <th>Moneda</th>
                                    <th class="text-end">Importe Total</th>
                                    <th class="text-end">Saldo Pendiente</th>
                                    <th class="w-1">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cuentas as $cuenta): ?>
                                    <tr>
                                        <td>
                                            <div><?= htmlspecialchars($cuenta['cliente_nombre']) ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($cuenta['numero_documento']) ?></div>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($cuenta['tipo_documento']) ?></div>
                                            <div class="text-muted"><?= htmlspecialchars($cuenta['serie'] . '-' . $cuenta['numero']) ?></div>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($cuenta['fecha_emision'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($cuenta['fecha_vencimiento'])) ?></td>
                                        <td>
                                            <span class="badge badge-danger badge-lg">
                                                <?= $cuenta['dias_vencidos'] ?> días
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($cuenta['moneda']) ?></td>
                                        <td class="text-end">
                                            <?= $cuenta['moneda'] === 'PEN' ? 'S/' : '$' ?>
                                            <?= number_format($cuenta['importe_total'], 2) ?>
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-danger">
                                                <?= $cuenta['moneda'] === 'PEN' ? 'S/' : '$' ?>
                                                <?= number_format($cuenta['saldo_pendiente'], 2) ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <a href="/cuentas-por-cobrar/ver/<?= $cuenta['id'] ?>" class="btn btn-sm btn-icon btn-primary">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7" class="text-end">Total:</th>
                                    <th class="text-end">
                                        <?php
                                        $totalPEN = 0;
                                        $totalUSD = 0;
                                        foreach ($cuentas as $c) {
                                            if ($c['moneda'] === 'PEN') {
                                                $totalPEN += $c['saldo_pendiente'];
                                            } elseif ($c['moneda'] === 'USD') {
                                                $totalUSD += $c['saldo_pendiente'];
                                            }
                                        }
                                        ?>
                                        <div class="text-danger">S/ <?= number_format($totalPEN, 2) ?></div>
                                        <?php if ($totalUSD > 0): ?>
                                            <div class="text-danger">$ <?= number_format($totalUSD, 2) ?></div>
                                        <?php endif; ?>
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
