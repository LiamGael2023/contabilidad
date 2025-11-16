<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-arrow-up me-2"></i>Registrar Egreso
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/movimientos-caja" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form method="POST" action="/movimientos-caja/guardar-egreso">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div class="card">
                <div class="card-header bg-danger-lt">
                    <h3 class="card-title text-danger">
                        <i class="ti ti-cash-off me-2"></i>Datos del Egreso
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Cuenta / Caja</label>
                            <select class="form-select" name="banco_id" required>
                                <option value="">Seleccione una cuenta...</option>
                                <?php foreach ($bancos as $banco): ?>
                                    <option value="<?= $banco['id'] ?>">
                                        <?= htmlspecialchars($banco['nombre_banco']) ?> (<?= $banco['moneda'] ?>)
                                        - Saldo: <?= $banco['moneda'] === 'PEN' ? 'S/' : '$' ?> <?= number_format($banco['saldo_actual'], 2) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Fecha</label>
                            <input type="date" class="form-control" name="fecha" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Tipo de Operación</label>
                            <select class="form-select" name="tipo_operacion" required>
                                <option value="pago">Pago</option>
                                <option value="transferencia">Transferencia Enviada</option>
                                <option value="cheque">Cheque</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label required">Moneda</label>
                            <select class="form-select" name="moneda" id="moneda" required>
                                <option value="PEN" selected>PEN - Soles</option>
                                <option value="USD">USD - Dólares</option>
                                <option value="EUR">EUR - Euros</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Tipo de Cambio</label>
                            <input type="number" class="form-control" name="tipo_cambio" id="tipo_cambio"
                                   value="<?= number_format($tipo_cambio ?? 1, 4) ?>" step="0.0001" required>
                            <small class="form-hint">Actual: <?= number_format($tipo_cambio ?? 1, 4) ?></small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text" id="simbolo-moneda">S/</span>
                                <input type="number" class="form-control" name="monto"
                                       step="0.01" min="0.01" required placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">N° Operación</label>
                            <input type="text" class="form-control" name="numero_operacion"
                                   placeholder="Opcional">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tercero (Cliente/Proveedor)</label>
                            <select class="form-select" name="tercero_id">
                                <option value="">Seleccione... (opcional)</option>
                                <?php foreach ($terceros as $tercero): ?>
                                    <option value="<?= $tercero['id'] ?>">
                                        <?= htmlspecialchars($tercero['razon_social']) ?> - <?= htmlspecialchars($tercero['numero_documento']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" name="categoria">
                                <option value="">Seleccione... (opcional)</option>
                                <option value="compras">Compras</option>
                                <option value="servicios">Servicios</option>
                                <option value="planilla">Planilla</option>
                                <option value="impuestos">Impuestos</option>
                                <option value="prestamos">Préstamos</option>
                                <option value="gastos_operativos">Gastos Operativos</option>
                                <option value="otros">Otros Egresos</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label required">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3" required
                                      placeholder="Descripción detallada del egreso"></textarea>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        <strong>Importante:</strong> Verifique que la cuenta tenga saldo suficiente antes de registrar el egreso.
                    </div>
                </div>
            </div>

            <div class="card-footer text-end mt-3">
                <div class="d-flex">
                    <a href="/movimientos-caja" class="btn btn-link">Cancelar</a>
                    <button type="submit" class="btn btn-danger ms-auto">
                        <i class="ti ti-device-floppy me-1"></i>Registrar Egreso
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const monedaSelect = document.getElementById('moneda');
    const simboloMoneda = document.getElementById('simbolo-moneda');
    const tipoCambioInput = document.getElementById('tipo_cambio');

    monedaSelect.addEventListener('change', function() {
        const simbolos = {
            'PEN': 'S/',
            'USD': '$',
            'EUR': '€'
        };
        simboloMoneda.textContent = simbolos[this.value] || 'S/';

        if (this.value === 'PEN') {
            tipoCambioInput.value = '1.0000';
            tipoCambioInput.readOnly = true;
        } else {
            tipoCambioInput.readOnly = false;
        }
    });
});
</script>
