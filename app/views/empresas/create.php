<div class="row">
    <div class="col-12">
        <h1><i class="bi bi-building"></i> Nueva Empresa</h1>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 col-xl-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/empresas/guardar">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <h5 class="card-title mb-3">Datos Fiscales</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="ruc" class="form-label">RUC *</label>
                            <input type="text" class="form-control" id="ruc" name="ruc"
                                   pattern="\d{11}" maxlength="11" required>
                            <small class="text-muted">11 dígitos</small>
                        </div>
                        <div class="col-md-8">
                            <label for="razon_social" class="form-label">Razón Social *</label>
                            <input type="text" class="form-control" id="razon_social"
                                   name="razon_social" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre_comercial" class="form-label">Nombre Comercial</label>
                        <input type="text" class="form-control" id="nombre_comercial"
                               name="nombre_comercial">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="regimen_tributario" class="form-label">Régimen Tributario *</label>
                            <select class="form-select" id="regimen_tributario"
                                    name="regimen_tributario" required>
                                <option value="">Seleccione...</option>
                                <option value="RG">Régimen General</option>
                                <option value="RER">Régimen Especial de Renta</option>
                                <option value="MYPE">Régimen MYPE Tributario</option>
                                <option value="NRUS">Nuevo RUS</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tipo_contribuyente" class="form-label">Tipo Contribuyente *</label>
                            <select class="form-select" id="tipo_contribuyente"
                                    name="tipo_contribuyente" required>
                                <option value="">Seleccione...</option>
                                <option value="PERSONA_JURIDICA" selected>Persona Jurídica</option>
                                <option value="PERSONA_NATURAL">Persona Natural</option>
                            </select>
                        </div>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Dirección</h5>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="departamento" class="form-label">Departamento</label>
                            <input type="text" class="form-control" id="departamento" name="departamento">
                        </div>
                        <div class="col-md-4">
                            <label for="provincia" class="form-label">Provincia</label>
                            <input type="text" class="form-control" id="provincia" name="provincia">
                        </div>
                        <div class="col-md-4">
                            <label for="distrito" class="form-label">Distrito</label>
                            <input type="text" class="form-control" id="distrito" name="distrito">
                        </div>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Contacto</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Actividad Económica</h5>

                    <div class="mb-3">
                        <label for="actividad_economica" class="form-label">Actividad Económica</label>
                        <input type="text" class="form-control" id="actividad_economica"
                               name="actividad_economica">
                    </div>

                    <div class="mb-3">
                        <label for="fecha_inicio_actividades" class="form-label">
                            Fecha Inicio de Actividades *
                        </label>
                        <input type="date" class="form-control" id="fecha_inicio_actividades"
                               name="fecha_inicio_actividades" required>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Representante Legal</h5>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="representante_legal" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="representante_legal"
                                   name="representante_legal">
                        </div>
                        <div class="col-md-4">
                            <label for="dni_representante" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="dni_representante"
                                   name="dni_representante" pattern="\d{8}" maxlength="8">
                            <small class="text-muted">8 dígitos</small>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="/empresas" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Empresa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
