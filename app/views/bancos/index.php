<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-building-bank me-2"></i>Caja y Bancos
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="/bancos/liquidez" class="btn btn-outline-info">
                        <i class="ti ti-chart-line me-1"></i>
                        Resumen de Liquidez
                    </a>
                    <a href="/movimientos-caja" class="btn btn-outline-primary">
                        <i class="ti ti-arrows-exchange me-1"></i>
                        Movimientos
                    </a>
                    <a href="/bancos/nuevo" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>
                        Nueva Cuenta
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Tarjetas de resumen de liquidez -->
        <div class="row row-cards mb-3">
            <div class="col-sm-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total en Soles (PEN)</div>
                        </div>
                        <div class="h1 mb-3 text-primary">S/ <?= number_format($total_pen ?? 0, 2) ?></div>
                        <div class="d-flex mb-2">
                            <div>Saldo total en cuentas activas</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total en Dólares (USD)</div>
                        </div>
                        <div class="h1 mb-3 text-success">$ <?= number_format($total_usd ?? 0, 2) ?></div>
                        <div class="d-flex mb-2">
                            <div>Saldo total en cuentas activas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de cuentas -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cuentas Bancarias y Cajas</h3>
            </div>
            <div class="card-body">
                <?php if (empty($bancos)): ?>
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-building-bank"></i>
                        </div>
                        <p class="empty-title">No hay cuentas registradas</p>
                        <p class="empty-subtitle text-muted">
                            Aún no se han registrado cuentas bancarias o cajas
                        </p>
                        <div class="empty-action">
                            <a href="/bancos/nuevo" class="btn btn-primary">
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
                                    <th>Tipo</th>
                                    <th>Nombre del Banco</th>
                                    <th>Número de Cuenta</th>
                                    <th>Moneda</th>
                                    <th class="text-end">Saldo Inicial</th>
                                    <th class="text-end">Saldo Actual</th>
                                    <th>Estado</th>
                                    <th class="w-1">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bancos as $banco): ?>
                                    <tr class="<?= !$banco['activo'] ? 'text-muted' : '' ?>">
                                        <td>
                                            <?php
                                            $tipos = [
                                                'caja' => '<i class="ti ti-cash me-1"></i>Caja',
                                                'cuenta_corriente' => '<i class="ti ti-building-bank me-1"></i>Cta. Corriente',
                                                'cuenta_ahorros' => '<i class="ti ti-pig-money me-1"></i>Ahorros',
                                                'cuenta_detracciones' => '<i class="ti ti-receipt-tax me-1"></i>Detracciones'
                                            ];
                                            echo $tipos[$banco['tipo_cuenta']] ?? htmlspecialchars($banco['tipo_cuenta']);
                                            ?>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($banco['nombre_banco']) ?></strong>
                                        </td>
                                        <td>
                                            <?= $banco['numero_cuenta'] ? htmlspecialchars($banco['numero_cuenta']) : '<span class="text-muted">-</span>' ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-outline <?= $banco['moneda'] === 'PEN' ? 'text-primary' : 'text-success' ?>">
                                                <?= htmlspecialchars($banco['moneda']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <?= $banco['moneda'] === 'PEN' ? 'S/' : '$' ?>
                                            <?= number_format($banco['saldo_inicial'], 2) ?>
                                        </td>
                                        <td class="text-end">
                                            <strong class="<?= $banco['saldo_actual'] < 0 ? 'text-danger' : 'text-success' ?>">
                                                <?= $banco['moneda'] === 'PEN' ? 'S/' : '$' ?>
                                                <?= number_format($banco['saldo_actual'], 2) ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="badge <?= $banco['activo'] ? 'badge-success' : 'badge-secondary' ?>">
                                                <?= $banco['activo'] ? 'Activo' : 'Inactivo' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <a href="/bancos/movimientos/<?= $banco['id'] ?>" class="btn btn-sm btn-icon btn-info" title="Ver movimientos">
                                                    <i class="ti ti-list"></i>
                                                </a>
                                                <a href="/bancos/editar/<?= $banco['id'] ?>" class="btn btn-sm btn-icon btn-primary" title="Editar">
                                                    <i class="ti ti-pencil"></i>
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
