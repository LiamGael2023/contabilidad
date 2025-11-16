<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-receipt me-2"></i>Cuentas por Pagar
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="/cuentas-por-pagar/vencidas" class="btn btn-outline-danger">
                        <i class="ti ti-alert-circle me-1"></i>
                        Vencidas (<?= $vencidas ?>)
                    </a>
                    <a href="/cuentas-por-pagar/por-vencer" class="btn btn-outline-warning">
                        <i class="ti ti-clock me-1"></i>
                        Por Vencer (<?= $por_vencer ?>)
                    </a>
                    <a href="/cuentas-por-pagar/antiguedad" class="btn btn-outline-info">
                        <i class="ti ti-chart-bar me-1"></i>
                        Antigüedad de Saldos
                    </a>
                    <a href="/cuentas-por-pagar/nuevo" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>
                        Nueva Cuenta por Pagar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Tarjetas de resumen -->
        <div class="row row-cards mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Pendiente</div>
                            <div class="ms-auto lh-1">
                                <a href="?estado=pendiente" class="btn btn-sm btn-ghost-primary">Ver</a>
                            </div>
                        </div>
                        <div class="h1 mb-3">S/ <?= number_format($totales['pendiente'] ?? 0, 2) ?></div>
                        <div class="d-flex mb-2">
                            <div>Cuentas sin pagar</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Pago Parcial</div>
                            <div class="ms-auto lh-1">
                                <a href="?estado=parcial" class="btn btn-sm btn-ghost-warning">Ver</a>
                            </div>
                        </div>
                        <div class="h1 mb-3">S/ <?= number_format($totales['parcial'] ?? 0, 2) ?></div>
                        <div class="d-flex mb-2">
                            <div>Con pagos parciales</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Pagado</div>
                            <div class="ms-auto lh-1">
                                <a href="?estado=pagado" class="btn btn-sm btn-ghost-success">Ver</a>
                            </div>
                        </div>
                        <div class="h1 mb-3">S/ <?= number_format($totales['pagado'] ?? 0, 2) ?></div>
                        <div class="d-flex mb-2">
                            <div>Cuentas canceladas</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Vencido</div>
                            <div class="ms-auto lh-1">
                                <a href="?estado=vencido" class="btn btn-sm btn-ghost-danger">Ver</a>
                            </div>
                        </div>
                        <div class="h1 mb-3">S/ <?= number_format($totales['vencido'] ?? 0, 2) ?></div>
                        <div class="d-flex mb-2">
                            <div>Fuera de plazo</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group" role="group">
                            <a href="?estado=todos" class="btn btn-sm <?= ($estado_actual === 'todos') ? 'btn-primary' : 'btn-outline-primary' ?>">
                                Todos
                            </a>
                            <a href="?estado=pendiente" class="btn btn-sm <?= ($estado_actual === 'pendiente') ? 'btn-primary' : 'btn-outline-primary' ?>">
                                Pendiente
                            </a>
                            <a href="?estado=parcial" class="btn btn-sm <?= ($estado_actual === 'parcial') ? 'btn-warning' : 'btn-outline-warning' ?>">
                                Pago Parcial
                            </a>
                            <a href="?estado=pagado" class="btn btn-sm <?= ($estado_actual === 'pagado') ? 'btn-success' : 'btn-outline-success' ?>">
                                Pagado
                            </a>
                            <a href="?estado=vencido" class="btn btn-sm <?= ($estado_actual === 'vencido') ? 'btn-danger' : 'btn-outline-danger' ?>">
                                Vencido
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de cuentas -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Cuentas por Pagar</h3>
            </div>
            <div class="card-body">
                <?php if (empty($cuentas)): ?>
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-receipt-off"></i>
                        </div>
                        <p class="empty-title">No hay cuentas por pagar</p>
                        <p class="empty-subtitle text-muted">
                            <?= $estado_actual === 'todos' ? 'Aún no se han registrado cuentas por pagar' : 'No hay cuentas con estado: ' . $estado_actual ?>
                        </p>
                        <div class="empty-action">
                            <a href="/cuentas-por-pagar/nuevo" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>
                                Registrar Primera Cuenta
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th>Proveedor</th>
                                    <th>Emisión</th>
                                    <th>Vencimiento</th>
                                    <th>Moneda</th>
                                    <th class="text-end">Importe Total</th>
                                    <th class="text-end">Saldo Pendiente</th>
                                    <th>Estado</th>
                                    <th class="w-1">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cuentas as $cuenta): ?>
                                    <tr>
                                        <td>
                                            <div><?= htmlspecialchars($cuenta['tipo_documento']) ?></div>
                                            <div class="text-muted"><?= htmlspecialchars($cuenta['serie'] . '-' . $cuenta['numero']) ?></div>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($cuenta['proveedor_nombre']) ?></div>
                                            <div class="text-muted"><?= htmlspecialchars($cuenta['numero_documento']) ?></div>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($cuenta['fecha_emision'])) ?></td>
                                        <td>
                                            <?= date('d/m/Y', strtotime($cuenta['fecha_vencimiento'])) ?>
                                            <?php
                                            $hoy = new DateTime();
                                            $venc = new DateTime($cuenta['fecha_vencimiento']);
                                            $diff = $hoy->diff($venc);
                                            if ($cuenta['estado'] !== 'pagado'):
                                                if ($hoy > $venc):
                                                    echo '<br><small class="text-danger">Vencido hace ' . $diff->days . ' días</small>';
                                                elseif ($diff->days <= 7):
                                                    echo '<br><small class="text-warning">Vence en ' . $diff->days . ' días</small>';
                                                endif;
                                            endif;
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($cuenta['moneda']) ?></td>
                                        <td class="text-end">
                                            <strong><?= number_format($cuenta['importe_total'], 2) ?></strong>
                                        </td>
                                        <td class="text-end">
                                            <strong class="<?= $cuenta['saldo_pendiente'] > 0 ? 'text-danger' : 'text-success' ?>">
                                                <?= number_format($cuenta['saldo_pendiente'], 2) ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <?php
                                            $badge_class = [
                                                'pendiente' => 'badge-warning',
                                                'parcial' => 'badge-info',
                                                'pagado' => 'badge-success',
                                                'vencido' => 'badge-danger'
                                            ];
                                            $estado_text = [
                                                'pendiente' => 'Pendiente',
                                                'parcial' => 'Pago Parcial',
                                                'pagado' => 'Pagado',
                                                'vencido' => 'Vencido'
                                            ];
                                            ?>
                                            <span class="badge <?= $badge_class[$cuenta['estado']] ?? 'badge-secondary' ?>">
                                                <?= $estado_text[$cuenta['estado']] ?? $cuenta['estado'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <a href="/cuentas-por-pagar/ver/<?= $cuenta['id'] ?>" class="btn btn-sm btn-icon btn-primary" title="Ver detalle">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </div>
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
