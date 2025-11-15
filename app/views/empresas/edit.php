<div class="row">
    <div class="col-12">
        <h1><i class="bi bi-building"></i> Editar Empresa</h1>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 col-xl-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/empresas/actualizar/<?= $empresa['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <h5 class="card-title mb-3">Datos Fiscales</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="ruc" class="form-label">RUC</label>
                            <input type="text" class="form-control" id="ruc"
                                   value="<?= htmlspecialchars($empresa['ruc']) ?>" disabled>
                            <small class="text-muted">No se puede modificar</small>
                        </div>
                        <div class="col-md-8">
                            <label for="razon_social" class="form-label">Razón Social *</label>
                            <input type="text" class="form-control" id="razon_social"
                                   name="razon_social"
                                   value="<?= htmlspecialchars($empresa['razon_social']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre_comercial" class="form-label">Nombre Comercial</label>
                        <input type="text" class="form-control" id="nombre_comercial"
                               name="nombre_comercial"
                               value="<?= htmlspecialchars($empresa['nombre_comercial'] ?? '') ?>">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="regimen_tributario" class="form-label">Régimen Tributario *</label>
                            <select class="form-select" id="regimen_tributario"
                                    name="regimen_tributario" required>
                                <option value="RG" <?= $empresa['regimen_tributario'] === 'RG' ? 'selected' : '' ?>>
                                    Régimen General
                                </option>
                                <option value="RER" <?= $empresa['regimen_tributario'] === 'RER' ? 'selected' : '' ?>>
                                    Régimen Especial de Renta
                                </option>
                                <option value="MYPE" <?= $empresa['regimen_tributario'] === 'MYPE' ? 'selected' : '' ?>>
                                    Régimen MYPE Tributario
                                </option>
                                <option value="NRUS" <?= $empresa['regimen_tributario'] === 'NRUS' ? 'selected' : '' ?>>
                                    Nuevo RUS
                                </option>
                            </select>
                        </div>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Dirección</h5>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion"
                               value="<?= htmlspecialchars($empresa['direccion'] ?? '') ?>">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="departamento" class="form-label">Departamento</label>
                            <input type="text" class="form-control" id="departamento" name="departamento"
                                   value="<?= htmlspecialchars($empresa['departamento'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="provincia" class="form-label">Provincia</label>
                            <input type="text" class="form-control" id="provincia" name="provincia"
                                   value="<?= htmlspecialchars($empresa['provincia'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="distrito" class="form-label">Distrito</label>
                            <input type="text" class="form-control" id="distrito" name="distrito"
                                   value="<?= htmlspecialchars($empresa['distrito'] ?? '') ?>">
                        </div>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Contacto</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono"
                                   value="<?= htmlspecialchars($empresa['telefono'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= htmlspecialchars($empresa['email'] ?? '') ?>">
                        </div>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Actividad Económica</h5>

                    <div class="mb-3">
                        <label for="actividad_economica" class="form-label">Actividad Económica</label>
                        <input type="text" class="form-control" id="actividad_economica"
                               name="actividad_economica"
                               value="<?= htmlspecialchars($empresa['actividad_economica'] ?? '') ?>">
                    </div>

                    <h5 class="card-title mb-3 mt-4">Representante Legal</h5>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="representante_legal" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="representante_legal"
                                   name="representante_legal"
                                   value="<?= htmlspecialchars($empresa['representante_legal'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="dni_representante" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="dni_representante"
                                   name="dni_representante" pattern="\d{8}" maxlength="8"
                                   value="<?= htmlspecialchars($empresa['dni_representante'] ?? '') ?>">
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="/empresas" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Empresa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
