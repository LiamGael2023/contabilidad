<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-plus me-2"></i>Registrar Cuenta Bancaria / Caja
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/bancos" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form method="POST" action="/bancos/guardar">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información de la Cuenta</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label required">Tipo de Cuenta</label>
                            <select class="form-select" name="tipo_cuenta" id="tipo_cuenta" required>
                                <option value="">Seleccione...</option>
                                <option value="caja">Caja</option>
                                <option value="cuenta_corriente">Cuenta Corriente</option>
                                <option value="cuenta_ahorros">Cuenta de Ahorros</option>
                                <option value="cuenta_detracciones">Cuenta de Detracciones</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label required">Nombre del Banco / Caja</label>
                            <input type="text" class="form-control" name="nombre_banco" required
                                   placeholder="Ej: BCP - Cuenta Principal, Caja Chica Oficina">
                            <small class="form-hint">Nombre descriptivo para identificar esta cuenta</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Número de Cuenta</label>
                            <input type="text" class="form-control" name="numero_cuenta"
                                   placeholder="Ej: 193-12345678-0-91" id="numero_cuenta">
                            <small class="form-hint">Opcional. Dejar vacío para cajas</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Moneda</label>
                            <select class="form-select" name="moneda" id="moneda" required>
                                <option value="PEN" selected>PEN - Soles</option>
                                <option value="USD">USD - Dólares</option>
                                <option value="EUR">EUR - Euros</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Saldo Inicial</label>
                            <div class="input-group">
                                <span class="input-group-text" id="simbolo-moneda">S/</span>
                                <input type="number" class="form-control" name="saldo_inicial"
                                       step="0.01" value="0.00" required>
                            </div>
                            <small class="form-hint">Saldo al crear la cuenta</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-end mt-3">
                <div class="d-flex">
                    <a href="/bancos" class="btn btn-link">Cancelar</a>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <i class="ti ti-device-floppy me-1"></i>Guardar Cuenta
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoCuentaSelect = document.getElementById('tipo_cuenta');
    const numeroCuentaInput = document.getElementById('numero_cuenta');
    const monedaSelect = document.getElementById('moneda');
    const simboloMoneda = document.getElementById('simbolo-moneda');

    // Cambiar símbolo de moneda
    monedaSelect.addEventListener('change', function() {
        const simbolos = {
            'PEN': 'S/',
            'USD': '$',
            'EUR': '€'
        };
        simboloMoneda.textContent = simbolos[this.value] || 'S/';
    });

    // Si es caja, número de cuenta no es requerido
    tipoCuentaSelect.addEventListener('change', function() {
        if (this.value === 'caja') {
            numeroCuentaInput.required = false;
            numeroCuentaInput.placeholder = 'No aplica para cajas';
        } else {
            numeroCuentaInput.required = false; // Opcional para todas
            numeroCuentaInput.placeholder = 'Ej: 193-12345678-0-91';
        }
    });
});
</script>
