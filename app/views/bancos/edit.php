<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-pencil me-2"></i>Editar Cuenta
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
        <form method="POST" action="/bancos/actualizar/<?= $banco['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información de la Cuenta</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label required">Tipo de Cuenta</label>
                            <select class="form-select" name="tipo_cuenta" required>
                                <option value="caja" <?= $banco['tipo_cuenta'] === 'caja' ? 'selected' : '' ?>>Caja</option>
                                <option value="cuenta_corriente" <?= $banco['tipo_cuenta'] === 'cuenta_corriente' ? 'selected' : '' ?>>Cuenta Corriente</option>
                                <option value="cuenta_ahorros" <?= $banco['tipo_cuenta'] === 'cuenta_ahorros' ? 'selected' : '' ?>>Cuenta de Ahorros</option>
                                <option value="cuenta_detracciones" <?= $banco['tipo_cuenta'] === 'cuenta_detracciones' ? 'selected' : '' ?>>Cuenta de Detracciones</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label required">Nombre del Banco / Caja</label>
                            <input type="text" class="form-control" name="nombre_banco" required
                                   value="<?= htmlspecialchars($banco['nombre_banco']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Número de Cuenta</label>
                            <input type="text" class="form-control" name="numero_cuenta"
                                   value="<?= htmlspecialchars($banco['numero_cuenta'] ?? '') ?>">
                            <small class="form-hint">Opcional. Dejar vacío para cajas</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Moneda</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($banco['moneda']) ?>" disabled>
                            <small class="form-hint text-danger">No se puede modificar</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="activo" required>
                                <option value="1" <?= $banco['activo'] ? 'selected' : '' ?>>Activo</option>
                                <option value="0" <?= !$banco['activo'] ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Saldos:</strong><br>
                        Saldo Inicial: <?= $banco['moneda'] === 'PEN' ? 'S/' : '$' ?> <?= number_format($banco['saldo_inicial'], 2) ?><br>
                        Saldo Actual: <?= $banco['moneda'] === 'PEN' ? 'S/' : '$' ?> <?= number_format($banco['saldo_actual'], 2) ?><br>
                        <small class="text-muted">Los saldos no pueden modificarse directamente. Use movimientos de caja para actualizar.</small>
                    </div>
                </div>
            </div>

            <div class="card-footer text-end mt-3">
                <div class="d-flex">
                    <a href="/bancos" class="btn btn-link">Cancelar</a>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <i class="ti ti-device-floppy me-1"></i>Actualizar Cuenta
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
