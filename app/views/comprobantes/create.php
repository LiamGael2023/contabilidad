<div class="row">
    <div class="col-12">
        <h1><i class="bi bi-receipt"></i> Nuevo Comprobante de Pago</h1>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/comprobantes/guardar">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <h5 class="card-title mb-3">Datos del Comprobante</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tipo_comprobante" class="form-label">Tipo de Comprobante *</label>
                            <select class="form-select" id="tipo_comprobante" name="tipo_comprobante" required>
                                <option value="">Seleccione...</option>
                                <option value="01">01 - Factura</option>
                                <option value="03">03 - Boleta de Venta</option>
                                <option value="07">07 - Nota de Crédito</option>
                                <option value="08">08 - Nota de Débito</option>
                                <option value="09">09 - Guía de Remisión</option>
                                <option value="12">12 - Ticket POS</option>
                                <option value="14">14 - Recibo por Servicios</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="serie" class="form-label">Serie *</label>
                            <input type="text" class="form-control" id="serie" name="serie"
                                   maxlength="4" required placeholder="F001, B001, etc.">
                        </div>
                        <div class="col-md-4">
                            <label for="numero" class="form-label">Número *</label>
                            <input type="text" class="form-control" id="numero" name="numero"
                                   maxlength="8" required placeholder="00000001">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fecha_emision" class="form-label">Fecha de Emisión *</label>
                            <input type="date" class="form-control" id="fecha_emision"
                                   name="fecha_emision" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                            <input type="date" class="form-control" id="fecha_vencimiento"
                                   name="fecha_vencimiento">
                        </div>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Datos del Cliente/Proveedor</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tercero_id" class="form-label">Cliente/Proveedor</label>
                            <select class="form-select" id="tercero_id" name="tercero_id">
                                <option value="">Seleccione...</option>
                                <?php foreach ($terceros as $tercero): ?>
                                    <option value="<?= $tercero['id'] ?>">
                                        <?= htmlspecialchars($tercero['numero_documento'] . ' - ' . $tercero['razon_social']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tipo_documento" class="form-label">Tipo Doc.</label>
                            <select class="form-select" id="tipo_documento" name="tipo_documento">
                                <option value="6">RUC</option>
                                <option value="1">DNI</option>
                                <option value="4">Carnet Ext.</option>
                                <option value="7">Pasaporte</option>
                                <option value="0">Otros</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="numero_documento" class="form-label">N° Documento</label>
                            <input type="text" class="form-control" id="numero_documento"
                                   name="numero_documento">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="razon_social" class="form-label">Razón Social / Nombre</label>
                        <input type="text" class="form-control" id="razon_social" name="razon_social">
                    </div>

                    <h5 class="card-title mb-3 mt-4">Montos</h5>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="moneda" class="form-label">Moneda *</label>
                            <select class="form-select" id="moneda" name="moneda" required>
                                <option value="PEN" selected>PEN - Soles</option>
                                <option value="USD">USD - Dólares</option>
                                <option value="EUR">EUR - Euros</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="subtotal" class="form-label">Subtotal</label>
                            <input type="number" class="form-control" id="subtotal" name="subtotal"
                                   step="0.01" value="0.00" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="igv" class="form-label">IGV (18%)</label>
                            <input type="number" class="form-control" id="igv" name="igv"
                                   step="0.01" value="0.00" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="total" class="form-label">Total *</label>
                            <input type="number" class="form-control" id="total" name="total"
                                   step="0.01" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones"
                                  rows="2"></textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Nota:</strong> Los comprobantes electrónicos deben ser enviados a SUNAT
                        de acuerdo a las normativas vigentes del sistema de facturación electrónica.
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="/comprobantes" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Comprobante
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Calcular IGV y Total
document.getElementById('total').addEventListener('input', function() {
    const total = parseFloat(this.value) || 0;
    const subtotal = total / 1.18;
    const igv = total - subtotal;

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('igv').value = igv.toFixed(2);
});

// Auto-completar datos del tercero seleccionado
document.getElementById('tercero_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (this.value) {
        // Aquí se podría hacer una llamada AJAX para obtener los datos completos
        // Por ahora solo limpiamos los campos para que el usuario los complete manualmente
    }
});
</script>
