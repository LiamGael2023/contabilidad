<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-plus me-2"></i>Registrar Cuenta por Cobrar
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
        <form method="POST" action="/cuentas-por-cobrar/guardar" id="formCuentaPorCobrar">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Datos del Documento</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label required">Tipo de Documento</label>
                            <select class="form-select" name="tipo_documento" required>
                                <option value="">Seleccione...</option>
                                <option value="Factura">Factura</option>
                                <option value="Boleta">Boleta</option>
                                <option value="Nota de Débito">Nota de Débito</option>
                                <option value="Recibo">Recibo por Honorarios</option>
                                <option value="Otros">Otros</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label required">Serie</label>
                            <input type="text" class="form-control" name="serie" required maxlength="4" placeholder="F001">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Número</label>
                            <input type="text" class="form-control" name="numero" required maxlength="10" placeholder="00000001">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Registro de Venta (Opcional)</label>
                            <input type="number" class="form-control" name="registro_venta_id" placeholder="ID del registro">
                            <small class="form-hint">Si proviene de un registro de ventas</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Cliente</label>
                            <select class="form-select" name="tercero_id" id="tercero_id" required>
                                <option value="">Seleccione un cliente...</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?= $cliente['id'] ?>"
                                            data-documento="<?= htmlspecialchars($cliente['numero_documento']) ?>">
                                        <?= htmlspecialchars($cliente['razon_social']) ?> - <?= htmlspecialchars($cliente['numero_documento']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Fecha de Emisión</label>
                            <input type="date" class="form-control" name="fecha_emision" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Fecha de Vencimiento</label>
                            <input type="date" class="form-control" name="fecha_vencimiento" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Montos</h3>
                </div>
                <div class="card-body">
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
                                   value="1.0000" step="0.0001" min="0" required>
                            <small class="form-hint">1.0000 para PEN</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Importe Total</label>
                            <div class="input-group">
                                <span class="input-group-text" id="simbolo-moneda">S/</span>
                                <input type="number" class="form-control" name="importe_total"
                                       step="0.01" min="0.01" required placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="3"
                                      placeholder="Información adicional sobre esta cuenta por cobrar"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-end mt-3">
                <div class="d-flex">
                    <a href="/cuentas-por-cobrar" class="btn btn-link">Cancelar</a>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <i class="ti ti-device-floppy me-1"></i>Guardar Cuenta por Cobrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cambiar símbolo de moneda
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

        // Si es PEN, poner tipo de cambio en 1
        if (this.value === 'PEN') {
            tipoCambioInput.value = '1.0000';
            tipoCambioInput.readOnly = true;
        } else {
            tipoCambioInput.readOnly = false;
            // Aquí podrías hacer una petición AJAX para obtener el tipo de cambio actual
        }
    });

    // Calcular fecha de vencimiento automáticamente (30 días después de emisión)
    const fechaEmision = document.querySelector('input[name="fecha_emision"]');
    const fechaVencimiento = document.querySelector('input[name="fecha_vencimiento"]');

    fechaEmision.addEventListener('change', function() {
        if (!fechaVencimiento.value) {
            const fecha = new Date(this.value);
            fecha.setDate(fecha.getDate() + 30);
            fechaVencimiento.value = fecha.toISOString().split('T')[0];
        }
    });
});
</script>
