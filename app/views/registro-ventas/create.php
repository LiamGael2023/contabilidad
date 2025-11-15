<div class="row">
    <div class="col-12">
        <h1><i class="bi bi-plus-square"></i> Registrar Venta</h1>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/registro-ventas/guardar">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label class="form-label">Correlativo</label>
                            <input type="number" class="form-control" name="correlativo"
                                   value="<?= $siguiente_correlativo ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha Emisión *</label>
                            <input type="date" class="form-control" name="fecha_emision"
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo Comprobante *</label>
                            <select class="form-select" name="tipo_comprobante" required>
                                <option value="01">01 - Factura</option>
                                <option value="03">03 - Boleta</option>
                                <option value="07">07 - Nota de Crédito</option>
                                <option value="08">08 - Nota de Débito</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Serie *</label>
                            <input type="text" class="form-control" name="serie" required maxlength="4">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Número *</label>
                            <input type="text" class="form-control" name="numero" required maxlength="8">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label class="form-label">Tipo Doc *</label>
                            <select class="form-select" name="tipo_documento_cliente" id="tipo_doc" required>
                                <option value="6">RUC</option>
                                <option value="1">DNI</option>
                                <option value="0">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">N° Documento *</label>
                            <input type="text" class="form-control" name="numero_documento_cliente" id="num_doc" required>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">Razón Social/Nombre *</label>
                            <input type="text" class="form-control" name="razon_social_cliente" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Base Imponible</label>
                            <input type="number" class="form-control" name="base_imponible"
                                   id="base" step="0.01" value="0.00">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">IGV (18%)</label>
                            <input type="number" class="form-control" name="igv"
                                   id="igv" step="0.01" value="0.00">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Exonerado</label>
                            <input type="number" class="form-control" name="exonerado"
                                   id="exonerado" step="0.01" value="0.00">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Inafecto</label>
                            <input type="number" class="form-control" name="inafecto"
                                   id="inafecto" step="0.01" value="0.00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Importe Total *</label>
                            <input type="number" class="form-control" name="importe_total"
                                   id="total" step="0.01" required>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Ingrese el total y el sistema calculará automáticamente la base imponible e IGV.
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="/registro-ventas" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Venta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('total').addEventListener('input', function() {
    const total = parseFloat(this.value) || 0;
    const base = total / 1.18;
    const igv = total - base;

    document.getElementById('base').value = base.toFixed(2);
    document.getElementById('igv').value = igv.toFixed(2);
});
</script>
