<div class="row">
    <div class="col-12">
        <h1><i class="bi bi-list-task"></i> Editar Cuenta Contable</h1>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/plan-contable/actualizar/<?= $cuenta['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="codigo"
                                   value="<?= htmlspecialchars($cuenta['codigo']) ?>" readonly>
                            <small class="text-muted">El código no se puede modificar</small>
                        </div>
                        <div class="col-md-4">
                            <label for="elemento" class="form-label">Elemento</label>
                            <input type="text" class="form-control" id="elemento"
                                   value="<?= htmlspecialchars($cuenta['elemento']) ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="nivel" class="form-label">Nivel</label>
                            <input type="text" class="form-control" id="nivel"
                                   value="<?= htmlspecialchars($cuenta['nivel']) ?>" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción *</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion"
                               value="<?= htmlspecialchars($cuenta['descripcion']) ?>"
                               required maxlength="200">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="naturaleza" class="form-label">Naturaleza *</label>
                            <select class="form-select" id="naturaleza" name="naturaleza" required>
                                <option value="D" <?= $cuenta['naturaleza'] === 'D' ? 'selected' : '' ?>>
                                    Deudora
                                </option>
                                <option value="A" <?= $cuenta['naturaleza'] === 'A' ? 'selected' : '' ?>>
                                    Acreedora
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tipo" class="form-label">Tipo *</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="A" <?= $cuenta['tipo'] === 'A' ? 'selected' : '' ?>>
                                    Activo
                                </option>
                                <option value="P" <?= $cuenta['tipo'] === 'P' ? 'selected' : '' ?>>
                                    Pasivo
                                </option>
                                <option value="PT" <?= $cuenta['tipo'] === 'PT' ? 'selected' : '' ?>>
                                    Patrimonio
                                </option>
                                <option value="I" <?= $cuenta['tipo'] === 'I' ? 'selected' : '' ?>>
                                    Ingreso
                                </option>
                                <option value="G" <?= $cuenta['tipo'] === 'G' ? 'selected' : '' ?>>
                                    Gasto
                                </option>
                                <option value="O" <?= $cuenta['tipo'] === 'O' ? 'selected' : '' ?>>
                                    Otro
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="recibe_saldo"
                                       name="recibe_saldo" value="1"
                                       <?= $cuenta['recibe_saldo'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="recibe_saldo">
                                    Recibe Saldo (puede registrar movimientos)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="requiere_auxiliar"
                                       name="requiere_auxiliar" value="1"
                                       <?= $cuenta['requiere_auxiliar'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="requiere_auxiliar">
                                    Requiere Auxiliar (centro de costo, documento, etc.)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Advertencia:</strong> Solo se pueden modificar los campos editables.
                        El código y la estructura jerárquica no se pueden cambiar una vez creada la cuenta.
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="/plan-contable" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Cuenta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
