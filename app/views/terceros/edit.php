<div class="row">
    <div class="col-12">
        <h1><i class="bi bi-pencil-square"></i> Editar Tercero</h1>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 col-xl-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/terceros/actualizar/<?= $tercero['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <h5 class="card-title mb-3">Tipo de Tercero</h5>

                    <div class="mb-3">
                        <label class="form-label">Tipo *</label>
                        <div class="form-selectgroup">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="tipo_tercero" value="cliente"
                                       class="form-selectgroup-input"
                                       <?= $tercero['tipo_tercero'] === 'cliente' ? 'checked' : '' ?>>
                                <span class="form-selectgroup-label">
                                    <i class="bi bi-person-check"></i> Cliente
                                </span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="tipo_tercero" value="proveedor"
                                       class="form-selectgroup-input"
                                       <?= $tercero['tipo_tercero'] === 'proveedor' ? 'checked' : '' ?>>
                                <span class="form-selectgroup-label">
                                    <i class="bi bi-truck"></i> Proveedor
                                </span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="tipo_tercero" value="ambos"
                                       class="form-selectgroup-input"
                                       <?= $tercero['tipo_tercero'] === 'ambos' ? 'checked' : '' ?>>
                                <span class="form-selectgroup-label">
                                    <i class="bi bi-people"></i> Ambos
                                </span>
                            </label>
                        </div>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Identificación</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                            <input type="text" class="form-control" readonly
                                   value="<?php
                                   $tipos_doc = [
                                       '1' => 'DNI',
                                       '6' => 'RUC',
                                       '4' => 'Carnet Extranjería',
                                       '7' => 'Pasaporte',
                                       '0' => 'Otros'
                                   ];
                                   echo htmlspecialchars($tipos_doc[$tercero['tipo_documento']] ?? $tercero['tipo_documento']);
                                   ?>">
                            <small class="text-muted">El tipo de documento no se puede modificar</small>
                        </div>
                        <div class="col-md-8">
                            <label for="numero_documento" class="form-label">Número de Documento</label>
                            <input type="text" class="form-control" readonly
                                   value="<?= htmlspecialchars($tercero['numero_documento']) ?>">
                            <small class="text-muted">El número de documento no se puede modificar</small>
                        </div>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Datos del Tercero</h5>

                    <div class="mb-3">
                        <label for="razon_social" class="form-label">Razón Social / Nombre Completo *</label>
                        <input type="text" class="form-control" id="razon_social"
                               name="razon_social" required maxlength="200"
                               value="<?= htmlspecialchars($tercero['razon_social']) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="nombre_comercial" class="form-label">Nombre Comercial</label>
                        <input type="text" class="form-control" id="nombre_comercial"
                               name="nombre_comercial" maxlength="200"
                               value="<?= htmlspecialchars($tercero['nombre_comercial'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion"
                               name="direccion" maxlength="250"
                               value="<?= htmlspecialchars($tercero['direccion'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="ubigeo" class="form-label">Ubigeo</label>
                        <input type="text" class="form-control" id="ubigeo" name="ubigeo"
                               maxlength="6" placeholder="000000"
                               value="<?= htmlspecialchars($tercero['ubigeo'] ?? '') ?>">
                        <small class="text-muted">Código de ubigeo de 6 dígitos (Dep-Prov-Dist)</small>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Datos de Contacto</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono"
                                   maxlength="20"
                                   value="<?= htmlspecialchars($tercero['telefono'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   maxlength="100"
                                   value="<?= htmlspecialchars($tercero['email'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Nota:</strong> El tipo y número de documento no pueden ser modificados
                        para mantener la integridad de los registros contables.
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="/terceros" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Tercero
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Solo números en ubigeo
document.getElementById('ubigeo').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
