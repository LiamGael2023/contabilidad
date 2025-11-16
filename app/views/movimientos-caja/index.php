<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-arrows-exchange me-2"></i>Movimientos de Caja
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="/movimientos-caja/flujo-diario" class="btn btn-outline-info">
                        <i class="ti ti-calendar me-1"></i>
                        Flujo Diario
                    </a>
                    <a href="/movimientos-caja/flujo-mensual" class="btn btn-outline-info">
                        <i class="ti ti-calendar-month me-1"></i>
                        Flujo Mensual
                    </a>
                    <a href="/movimientos-caja/ingreso" class="btn btn-success">
                        <i class="ti ti-arrow-down me-1"></i>
                        Registrar Ingreso
                    </a>
                    <a href="/movimientos-caja/egreso" class="btn btn-danger">
                        <i class="ti ti-arrow-up me-1"></i>
                        Registrar Egreso
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Resumen de movimientos -->
        <div class="row row-cards mb-3">
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Total Ingresos</div>
                        <div class="h1 mb-3 text-success">S/ <?= number_format($total_ingresos ?? 0, 2) ?></div>
                        <div class="d-flex mb-2">
                            <div>Período seleccionado</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Total Egresos</div>
                        <div class="h1 mb-3 text-danger">S/ <?= number_format($total_egresos ?? 0, 2) ?></div>
                        <div class="d-flex mb-2">
                            <div>Período seleccionado</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Flujo Neto</div>
                        <div class="h1 mb-3 <?= ($total_ingresos - $total_egresos) >= 0 ? 'text-success' : 'text-danger' ?>">
                            S/ <?= number_format(($total_ingresos ?? 0) - ($total_egresos ?? 0), 2) ?>
                        </div>
                        <div class="d-flex mb-2">
                            <div>Diferencia entre ingresos y egresos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="/movimientos-caja">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Cuenta</label>
                            <select name="banco_id" class="form-select">
                                <option value="todos" <?= ($banco_id ?? 'todos') === 'todos' ? 'selected' : '' ?>>Todas las cuentas</option>
                                <?php foreach ($bancos as $b): ?>
                                    <option value="<?= $b['id'] ?>" <?= ($banco_id ?? '') == $b['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($b['nombre_banco']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Desde</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="<?= $fecha_inicio ?? date('Y-m-01') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Hasta</label>
                            <input type="date" name="fecha_fin" class="form-control" value="<?= $fecha_fin ?? date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-filter me-1"></i>Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de movimientos -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Historial de Movimientos</h3>
            </div>
            <div class="card-body">
                <?php if (empty($movimientos)): ?>
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-arrows-exchange"></i>
                        </div>
                        <p class="empty-title">No hay movimientos registrados</p>
                        <p class="empty-subtitle text-muted">
                            No se encontraron movimientos en el período seleccionado
                        </p>
                        <div class="empty-action">
                            <a href="/movimientos-caja/ingreso" class="btn btn-success me-2">
                                <i class="ti ti-arrow-down me-1"></i>
                                Registrar Ingreso
                            </a>
                            <a href="/movimientos-caja/egreso" class="btn btn-danger">
                                <i class="ti ti-arrow-up me-1"></i>
                                Registrar Egreso
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Cuenta</th>
                                    <th>Operación</th>
                                    <th>Descripción</th>
                                    <th>Moneda</th>
                                    <th class="text-end">Monto</th>
                                    <th>N° Operación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($movimientos as $mov): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($mov['fecha'])) ?></td>
                                        <td>
                                            <?php if ($mov['tipo_movimiento'] === 'ingreso'): ?>
                                                <span class="badge badge-success">
                                                    <i class="ti ti-arrow-down me-1"></i>Ingreso
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">
                                                    <i class="ti ti-arrow-up me-1"></i>Egreso
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($mov['nombre_banco'] ?? 'N/A') ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($mov['tipo_cuenta'] ?? '') ?></div>
                                        </td>
                                        <td>
                                            <?php
                                            $operaciones = [
                                                'deposito' => 'Depósito',
                                                'transferencia' => 'Transferencia',
                                                'cheque' => 'Cheque',
                                                'efectivo' => 'Efectivo',
                                                'pago' => 'Pago',
                                                'cobro' => 'Cobro',
                                                'otros' => 'Otros'
                                            ];
                                            echo $operaciones[$mov['tipo_operacion']] ?? $mov['tipo_operacion'];
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($mov['descripcion']) ?></td>
                                        <td>
                                            <span class="badge badge-outline <?= $mov['moneda'] === 'PEN' ? 'text-primary' : 'text-success' ?>">
                                                <?= htmlspecialchars($mov['moneda']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <strong class="<?= $mov['tipo_movimiento'] === 'ingreso' ? 'text-success' : 'text-danger' ?>">
                                                <?= $mov['tipo_movimiento'] === 'ingreso' ? '+' : '-' ?>
                                                <?= $mov['moneda'] === 'PEN' ? 'S/' : '$' ?>
                                                <?= number_format($mov['monto'], 2) ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <?= $mov['numero_operacion'] ? htmlspecialchars($mov['numero_operacion']) : '<span class="text-muted">-</span>' ?>
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
