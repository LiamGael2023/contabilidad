<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-file-invoice me-2"></i>Detalle de Cuenta por Cobrar
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
        <div class="row">
            <div class="col-md-8">
                <!-- Información de la cuenta -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Información del Documento</h3>
                        <div class="card-actions">
                            <?php
                            $badge_class = [
                                'pendiente' => 'badge-warning',
                                'parcial' => 'badge-info',
                                'pagado' => 'badge-success',
                                'vencido' => 'badge-danger'
                            ];
                            ?>
                            <span class="badge <?= $badge_class[$cuenta['estado']] ?? 'badge-secondary' ?> badge-lg">
                                <?= strtoupper($cuenta['estado']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Tipo de Documento:</strong><br>
                                <?= htmlspecialchars($cuenta['tipo_documento']) ?>
                            </div>
                            <div class="col-md-6">
                                <strong>Serie - Número:</strong><br>
                                <?= htmlspecialchars($cuenta['serie'] . ' - ' . $cuenta['numero']) ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Cliente:</strong><br>
                                <?= htmlspecialchars($cuenta['cliente_nombre'] ?? 'N/A') ?><br>
                                <small class="text-muted"><?= htmlspecialchars($cuenta['numero_documento'] ?? '') ?></small>
                            </div>
                            <div class="col-md-3">
                                <strong>Fecha Emisión:</strong><br>
                                <?= date('d/m/Y', strtotime($cuenta['fecha_emision'])) ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Fecha Vencimiento:</strong><br>
                                <?= date('d/m/Y', strtotime($cuenta['fecha_vencimiento'])) ?>
                                <?php
                                $hoy = new DateTime();
                                $venc = new DateTime($cuenta['fecha_vencimiento']);
                                if ($hoy > $venc && $cuenta['estado'] !== 'pagado'):
                                    $diff = $hoy->diff($venc);
                                    echo '<br><small class="text-danger">Vencido hace ' . $diff->days . ' días</small>';
                                endif;
                                ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Moneda:</strong><br>
                                <span class="badge badge-outline text-primary"><?= htmlspecialchars($cuenta['moneda']) ?></span>
                            </div>
                            <div class="col-md-4">
                                <strong>Tipo de Cambio:</strong><br>
                                <?= number_format($cuenta['tipo_cambio'], 4) ?>
                            </div>
                            <div class="col-md-4">
                                <strong>Importe Total:</strong><br>
                                <span class="h3">
                                    <?= $cuenta['moneda'] === 'PEN' ? 'S/' : '$' ?>
                                    <?= number_format($cuenta['importe_total'], 2) ?>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Observaciones:</strong><br>
                                <?= $cuenta['observaciones'] ? htmlspecialchars($cuenta['observaciones']) : '<small class="text-muted">Sin observaciones</small>' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial de pagos -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Historial de Pagos</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($pagos)): ?>
                            <div class="empty">
                                <p class="empty-title">No hay pagos registrados</p>
                                <p class="empty-subtitle text-muted">
                                    Esta cuenta aún no tiene pagos aplicados
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Monto</th>
                                            <th>Método</th>
                                            <th>N° Operación</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pagos as $pago): ?>
                                            <tr>
                                                <td><?= date('d/m/Y', strtotime($pago['fecha_pago'])) ?></td>
                                                <td>
                                                    <strong class="text-success">
                                                        <?= $cuenta['moneda'] === 'PEN' ? 'S/' : '$' ?>
                                                        <?= number_format($pago['monto'], 2) ?>
                                                    </strong>
                                                </td>
                                                <td><?= htmlspecialchars($pago['metodo_pago']) ?></td>
                                                <td><?= $pago['numero_operacion'] ? htmlspecialchars($pago['numero_operacion']) : '-' ?></td>
                                                <td><?= $pago['observaciones'] ? htmlspecialchars($pago['observaciones']) : '-' ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Resumen de saldo -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Resumen de Saldo</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-muted">Importe Total</div>
                            <div class="h3">
                                <?= $cuenta['moneda'] === 'PEN' ? 'S/' : '$' ?>
                                <?= number_format($cuenta['importe_total'], 2) ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted">Total Pagado</div>
                            <div class="h3 text-success">
                                <?= $cuenta['moneda'] === 'PEN' ? 'S/' : '$' ?>
                                <?= number_format($cuenta['importe_total'] - $cuenta['saldo_pendiente'], 2) ?>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <div class="text-muted">Saldo Pendiente</div>
                            <div class="h2 <?= $cuenta['saldo_pendiente'] > 0 ? 'text-danger' : 'text-success' ?>">
                                <?= $cuenta['moneda'] === 'PEN' ? 'S/' : '$' ?>
                                <?= number_format($cuenta['saldo_pendiente'], 2) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registrar pago -->
                <?php if ($cuenta['saldo_pendiente'] > 0): ?>
                <div class="card mt-3">
                    <div class="card-header bg-success-lt">
                        <h3 class="card-title text-success">Registrar Pago</h3>
                    </div>
                    <div class="card-body">
                        <form id="formRegistrarPago">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <input type="hidden" name="cuenta_id" value="<?= $cuenta['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label required">Fecha de Pago</label>
                                <input type="date" class="form-control" name="fecha_pago" value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Monto</label>
                                <div class="input-group">
                                    <span class="input-group-text"><?= $cuenta['moneda'] === 'PEN' ? 'S/' : '$' ?></span>
                                    <input type="number" class="form-control" name="monto" step="0.01"
                                           max="<?= $cuenta['saldo_pendiente'] ?>" required placeholder="0.00">
                                </div>
                                <small class="form-hint">Máximo: <?= number_format($cuenta['saldo_pendiente'], 2) ?></small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Método de Pago</label>
                                <select class="form-select" name="metodo_pago" required>
                                    <option value="">Seleccione...</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="deposito">Depósito</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="otros">Otros</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">N° Operación</label>
                                <input type="text" class="form-control" name="numero_operacion" placeholder="Opcional">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Banco (Opcional)</label>
                                <input type="number" class="form-control" name="banco_id" placeholder="ID del banco">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observaciones</label>
                                <textarea class="form-control" name="observaciones" rows="2"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                <i class="ti ti-cash me-1"></i>
                                Registrar Pago
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formRegistrarPago')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    fetch('/cuentas-por-cobrar/registrar-pago', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pago registrado exitosamente');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error al registrar el pago');
        console.error('Error:', error);
    });
});
</script>
