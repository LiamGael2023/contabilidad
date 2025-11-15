<div class="row">
    <div class="col-12">
        <h1><i class="bi bi-list-task"></i> Nueva Cuenta Contable</h1>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/plan-contable/guardar">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="codigo" class="form-label">Código *</label>
                            <input type="text" class="form-control" id="codigo" name="codigo"
                                   required maxlength="20">
                            <small class="text-muted">Ej: 10, 101, 1011, etc.</small>
                        </div>
                        <div class="col-md-4">
                            <label for="elemento" class="form-label">Elemento *</label>
                            <select class="form-select" id="elemento" name="elemento" required>
                                <option value="">Seleccione...</option>
                                <option value="1">1 - Activo Disponible y Exigible</option>
                                <option value="2">2 - Activo Realizable</option>
                                <option value="3">3 - Activo Inmovilizado</option>
                                <option value="4">4 - Pasivo</option>
                                <option value="5">5 - Patrimonio</option>
                                <option value="6">6 - Gastos por Naturaleza</option>
                                <option value="7">7 - Ingresos</option>
                                <option value="8">8 - Saldos Intermediarios</option>
                                <option value="9">9 - Contabilidad Analítica</option>
                                <option value="0">0 - Cuentas de Orden</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="nivel" class="form-label">Nivel *</label>
                            <select class="form-select" id="nivel" name="nivel" required>
                                <option value="">Seleccione...</option>
                                <option value="1">1 - Elemento (1 dígito)</option>
                                <option value="2">2 - Cuenta (2 dígitos)</option>
                                <option value="3">3 - Subcuenta (3 dígitos)</option>
                                <option value="4">4 - Divisionaria (4 dígitos)</option>
                                <option value="5">5 - Subdivisionaria (5+ dígitos)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción *</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion"
                               required maxlength="200">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="codigo_padre" class="form-label">Código Padre</label>
                            <input type="text" class="form-control" id="codigo_padre" name="codigo_padre"
                                   maxlength="20">
                            <small class="text-muted">Cuenta superior en jerarquía</small>
                        </div>
                        <div class="col-md-4">
                            <label for="naturaleza" class="form-label">Naturaleza *</label>
                            <select class="form-select" id="naturaleza" name="naturaleza" required>
                                <option value="">Seleccione...</option>
                                <option value="D">Deudora</option>
                                <option value="A">Acreedora</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tipo" class="form-label">Tipo *</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Seleccione...</option>
                                <option value="A">Activo</option>
                                <option value="P">Pasivo</option>
                                <option value="PT">Patrimonio</option>
                                <option value="I">Ingreso</option>
                                <option value="G">Gasto</option>
                                <option value="O">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="recibe_saldo"
                                       name="recibe_saldo" value="1">
                                <label class="form-check-label" for="recibe_saldo">
                                    Recibe Saldo (puede registrar movimientos)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="requiere_auxiliar"
                                       name="requiere_auxiliar" value="1">
                                <label class="form-check-label" for="requiere_auxiliar">
                                    Requiere Auxiliar (centro de costo, documento, etc.)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Nota:</strong> El Plan Contable General Empresarial (PCGE) sigue la
                        estructura establecida por SUNAT para Perú. Asegúrese de respetar la jerarquía
                        y nomenclatura oficial.
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="/plan-contable" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cuenta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-determinar nivel basado en longitud del código
document.getElementById('codigo').addEventListener('input', function() {
    const codigo = this.value.trim();
    const nivel = document.getElementById('nivel');
    const elemento = document.getElementById('elemento');

    if (codigo.length > 0) {
        // Auto-seleccionar elemento
        elemento.value = codigo.charAt(0);

        // Auto-seleccionar nivel
        if (codigo.length === 1) {
            nivel.value = '1';
        } else if (codigo.length === 2) {
            nivel.value = '2';
        } else if (codigo.length === 3) {
            nivel.value = '3';
        } else if (codigo.length === 4) {
            nivel.value = '4';
        } else {
            nivel.value = '5';
        }
    }
});
</script>
