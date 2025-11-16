<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-clock me-2"></i>Cuentas por Vencer
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <form method="GET" action="/cuentas-por-cobrar/por-vencer" class="d-inline-flex align-items-center">
                        <label class="me-2">Próximos:</label>
                        <select name="dias" class="form-select form-select-sm w-auto me-2" onchange="this.form.submit()">
                            <option value="7" <?= ($dias ?? 30) == 7 ? 'selected' : '' ?>>7 días</option>
                            <option value="15" <?= ($dias ?? 30) == 15 ? 'selected' : '' ?>>15 días</option>
                            <option value="30" <?= ($dias ?? 30) == 30 ? 'selected' : '' ?>>30 días</option>
                            <option value="60" <?= ($dias ?? 30) == 60 ? 'selected' : '' ?>>60 días</option>
                        </select>
                    </form>
                    <a href="/cuentas-por-cobrar" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cuentas que Vencen en los Próximos <?= $dias ?> Días</h3>
            </div>
            <div class="card-body">
                <?php if (empty($cuentas)): ?>
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-check"></i>
                        </div>
                        <p class="empty-title">No hay cuentas por vencer</p>
                        <p class="empty-subtitle text-muted">
                            No hay cuentas que venzan en los próximos <?= $dias ?> días
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
                                    <th>Días Restantes</th>
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
                                            <span class="badge <?= $cuenta['dias_restantes'] <= 7 ? 'badge-warning' : 'badge-info' ?> badge-lg">
                                                <?= $cuenta['dias_restantes'] ?> días
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($cuenta['moneda']) ?></td>
                                        <td class="text-end">
                                            <?= $cuenta['moneda'] === 'PEN' ? 'S/' : '$' ?>
                                            <?= number_format($cuenta['importe_total'], 2) ?>
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-warning">
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
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
