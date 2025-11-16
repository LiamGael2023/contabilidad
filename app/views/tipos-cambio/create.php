<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-plus me-2"></i>Registrar Tipo de Cambio
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/tipos-cambio" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form method="POST" action="/tipos-cambio/guardar">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Datos del Tipo de Cambio</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label required">Fecha</label>
                            <input type="date" class="form-control" name="fecha" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Moneda</label>
                            <select class="form-select" name="moneda" required>
                                <option value="USD" selected>USD - Dólar Americano</option>
                                <option value="EUR">EUR - Euro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Fuente</label>
                            <select class="form-select" name="fuente" required>
                                <option value="Manual" selected>Manual</option>
                                <option value="SUNAT">SUNAT</option>
                                <option value="SBS">SBS - Superintendencia de Banca</option>
                                <option value="Banco">Banco</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Tipo de Cambio Compra</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="number" class="form-control" name="compra" id="compra"
                                       step="0.0001" min="0.0001" required placeholder="0.0000">
                            </div>
                            <small class="form-hint">Precio al que se compra la moneda extranjera</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Tipo de Cambio Venta</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="number" class="form-control" name="venta" id="venta"
                                       step="0.0001" min="0.0001" required placeholder="0.0000">
                            </div>
                            <small class="form-hint">Precio al que se vende la moneda extranjera</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <div>
                                        <i class="ti ti-info-circle me-2"></i>
                                        <strong>Información:</strong><br>
                                        El tipo de cambio promedio será: <span id="promedio" class="fw-bold">0.0000</span><br>
                                        <small>Promedio = (Compra + Venta) / 2</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        <strong>Nota:</strong> Si ya existe un tipo de cambio para esta fecha y moneda, será actualizado.
                    </div>
                </div>
            </div>

            <div class="card-footer text-end mt-3">
                <div class="d-flex">
                    <a href="/tipos-cambio" class="btn btn-link">Cancelar</a>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <i class="ti ti-device-floppy me-1"></i>Guardar Tipo de Cambio
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const compraInput = document.getElementById('compra');
    const ventaInput = document.getElementById('venta');
    const promedioSpan = document.getElementById('promedio');

    function calcularPromedio() {
        const compra = parseFloat(compraInput.value) || 0;
        const venta = parseFloat(ventaInput.value) || 0;
        const promedio = (compra + venta) / 2;
        promedioSpan.textContent = promedio.toFixed(4);
    }

    compraInput.addEventListener('input', calcularPromedio);
    ventaInput.addEventListener('input', calcularPromedio);

    // Validar que venta sea mayor que compra
    ventaInput.addEventListener('blur', function() {
        const compra = parseFloat(compraInput.value) || 0;
        const venta = parseFloat(this.value) || 0;

        if (venta > 0 && compra > 0 && venta < compra) {
            alert('El tipo de cambio de venta debe ser mayor o igual al de compra');
            this.value = '';
            this.focus();
        }
    });
});
</script>
