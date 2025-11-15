<div class="row">
    <div class="col-12">
        <h1><i class="bi bi-person-plus"></i> Nuevo Tercero</h1>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 col-xl-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/terceros/guardar" id="formTercero">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <h5 class="card-title mb-3">Tipo de Tercero</h5>

                    <div class="mb-3">
                        <label class="form-label">Tipo *</label>
                        <div class="form-selectgroup">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="tipo_tercero" value="cliente" class="form-selectgroup-input" checked>
                                <span class="form-selectgroup-label">
                                    <i class="bi bi-person-check"></i> Cliente
                                </span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="tipo_tercero" value="proveedor" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">
                                    <i class="bi bi-truck"></i> Proveedor
                                </span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="tipo_tercero" value="ambos" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">
                                    <i class="bi bi-people"></i> Ambos
                                </span>
                            </label>
                        </div>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Identificación</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tipo_documento" class="form-label">Tipo de Documento *</label>
                            <select class="form-select" id="tipo_documento" name="tipo_documento" required>
                                <option value="">Seleccione...</option>
                                <option value="6" selected>RUC</option>
                                <option value="1">DNI</option>
                                <option value="4">Carnet Extranjería</option>
                                <option value="7">Pasaporte</option>
                                <option value="0">Otros</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="numero_documento" class="form-label">Número de Documento *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="numero_documento"
                                       name="numero_documento" required maxlength="20">
                                <button type="button" class="btn btn-primary" id="btnConsultar">
                                    <i class="bi bi-search"></i> Consultar SUNAT
                                </button>
                            </div>
                            <small class="text-muted" id="helpDocumento">RUC: 11 dígitos</small>
                        </div>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Datos del Tercero</h5>

                    <div class="mb-3">
                        <label for="razon_social" class="form-label">Razón Social / Nombre Completo *</label>
                        <input type="text" class="form-control" id="razon_social"
                               name="razon_social" required maxlength="200">
                    </div>

                    <div class="mb-3">
                        <label for="nombre_comercial" class="form-label">Nombre Comercial</label>
                        <input type="text" class="form-control" id="nombre_comercial"
                               name="nombre_comercial" maxlength="200">
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion"
                               name="direccion" maxlength="250">
                    </div>

                    <div class="mb-3">
                        <label for="ubigeo" class="form-label">Ubigeo</label>
                        <input type="text" class="form-control" id="ubigeo" name="ubigeo"
                               maxlength="6" placeholder="000000">
                        <small class="text-muted">Código de ubigeo de 6 dígitos (Dep-Prov-Dist)</small>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Datos de Contacto</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono"
                                   maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   maxlength="100">
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Nota:</strong> Los terceros son clientes y/o proveedores con los que
                        la empresa realiza transacciones comerciales.
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="/terceros" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Tercero
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Cambiar ayuda según tipo de documento
document.getElementById('tipo_documento').addEventListener('change', function() {
    const help = document.getElementById('helpDocumento');
    const input = document.getElementById('numero_documento');

    switch(this.value) {
        case '6': // RUC
            help.textContent = 'RUC: 11 dígitos';
            input.maxLength = 11;
            break;
        case '1': // DNI
            help.textContent = 'DNI: 8 dígitos';
            input.maxLength = 8;
            break;
        case '4': // CE
            help.textContent = 'Carnet de Extranjería';
            input.maxLength = 12;
            break;
        case '7': // Pasaporte
            help.textContent = 'Pasaporte';
            input.maxLength = 12;
            break;
        default:
            help.textContent = '';
            input.maxLength = 20;
    }
});

// Consultar SUNAT (simulado)
document.getElementById('btnConsultar').addEventListener('click', function() {
    const tipoDoc = document.getElementById('tipo_documento').value;
    const numDoc = document.getElementById('numero_documento').value;

    if (!numDoc) {
        alert('Ingrese el número de documento');
        return;
    }

    if (tipoDoc === '6' && numDoc.length !== 11) {
        alert('El RUC debe tener 11 dígitos');
        return;
    }

    if (tipoDoc === '1' && numDoc.length !== 8) {
        alert('El DNI debe tener 8 dígitos');
        return;
    }

    // Aquí se haría la consulta real a SUNAT
    alert('Funcionalidad de consulta a SUNAT - Por implementar\n\nSe conectaría a la API de SUNAT para obtener datos automáticamente.');
});

// Solo números en documento
document.getElementById('numero_documento').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Solo números en ubigeo
document.getElementById('ubigeo').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
