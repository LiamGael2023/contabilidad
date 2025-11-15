<div class="row">
    <div class="col-12">
        <h1><i class="bi bi-journal-text"></i> Nuevo Asiento Contable</h1>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/asientos/guardar" id="formAsiento">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="numero_asiento" class="form-label">N° Asiento *</label>
                            <input type="text" class="form-control" id="numero_asiento"
                                   name="numero_asiento" value="<?= htmlspecialchars($siguiente_numero) ?>"
                                   readonly required>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha" class="form-label">Fecha *</label>
                            <input type="date" class="form-control" id="fecha"
                                   name="fecha" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="tipo_cambio" class="form-label">Tipo Cambio</label>
                            <input type="number" class="form-control" id="tipo_cambio"
                                   name="tipo_cambio" value="1.0000" step="0.0001">
                        </div>
                        <div class="col-md-3">
                            <label for="periodo" class="form-label">Período</label>
                            <input type="text" class="form-control" id="periodo"
                                   value="<?= htmlspecialchars($periodo['mes'] . '/' . $periodo['anio']) ?>"
                                   readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="glosa" class="form-label">Glosa *</label>
                        <textarea class="form-control" id="glosa" name="glosa"
                                  rows="2" required></textarea>
                    </div>

                    <h5 class="card-title mb-3">Detalles del Asiento</h5>

                    <div class="table-responsive">
                        <table class="table table-sm" id="tablaDetalles">
                            <thead>
                                <tr>
                                    <th width="40%">Cuenta</th>
                                    <th width="30%">Glosa</th>
                                    <th width="15%">Debe</th>
                                    <th width="15%">Haber</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="detallesBody">
                                <tr>
                                    <td>
                                        <select class="form-select form-select-sm" name="cuenta_id[]" required>
                                            <option value="">Seleccione cuenta...</option>
                                            <?php foreach ($cuentas as $cuenta): ?>
                                                <option value="<?= $cuenta['id'] ?>">
                                                    <?= htmlspecialchars($cuenta['codigo'] . ' - ' . $cuenta['descripcion']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                               name="glosa_detalle[]">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm debe-input"
                                               name="debe[]" step="0.01" min="0" value="0.00">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm haber-input"
                                               name="haber[]" step="0.01" min="0" value="0.00">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="eliminarFila(this)" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-select form-select-sm" name="cuenta_id[]" required>
                                            <option value="">Seleccione cuenta...</option>
                                            <?php foreach ($cuentas as $cuenta): ?>
                                                <option value="<?= $cuenta['id'] ?>">
                                                    <?= htmlspecialchars($cuenta['codigo'] . ' - ' . $cuenta['descripcion']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                               name="glosa_detalle[]">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm debe-input"
                                               name="debe[]" step="0.01" min="0" value="0.00">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm haber-input"
                                               name="haber[]" step="0.01" min="0" value="0.00">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="eliminarFila(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end"><strong>TOTALES:</strong></td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                               id="totalDebe" value="0.00" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                               id="totalHaber" value="0.00" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end"><strong>DIFERENCIA:</strong></td>
                                    <td colspan="2">
                                        <input type="text" class="form-control form-control-sm"
                                               id="diferencia" value="0.00" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <button type="button" class="btn btn-sm btn-success mb-3" onclick="agregarFila()">
                        <i class="bi bi-plus-circle"></i> Agregar Línea
                    </button>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="/asientos" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" id="btnGuardar">
                            <i class="bi bi-save"></i> Guardar Asiento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const cuentasOptions = `
    <option value="">Seleccione cuenta...</option>
    <?php foreach ($cuentas as $cuenta): ?>
        <option value="<?= $cuenta['id'] ?>">
            <?= htmlspecialchars($cuenta['codigo'] . ' - ' . $cuenta['descripcion']) ?>
        </option>
    <?php endforeach; ?>
`;

function agregarFila() {
    const tbody = document.getElementById('detallesBody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <select class="form-select form-select-sm" name="cuenta_id[]">
                ${cuentasOptions}
            </select>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="glosa_detalle[]">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm debe-input"
                   name="debe[]" step="0.01" min="0" value="0.00">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm haber-input"
                   name="haber[]" step="0.01" min="0" value="0.00">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="eliminarFila(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    actualizarEventos();
}

function eliminarFila(btn) {
    btn.closest('tr').remove();
    calcularTotales();
}

function calcularTotales() {
    let totalDebe = 0;
    let totalHaber = 0;

    document.querySelectorAll('.debe-input').forEach(input => {
        totalDebe += parseFloat(input.value) || 0;
    });

    document.querySelectorAll('.haber-input').forEach(input => {
        totalHaber += parseFloat(input.value) || 0;
    });

    document.getElementById('totalDebe').value = totalDebe.toFixed(2);
    document.getElementById('totalHaber').value = totalHaber.toFixed(2);

    const diferencia = totalDebe - totalHaber;
    const inputDiferencia = document.getElementById('diferencia');
    inputDiferencia.value = diferencia.toFixed(2);

    // Marcar si está balanceado
    if (Math.abs(diferencia) < 0.01) {
        inputDiferencia.style.backgroundColor = '#d4edda';
        document.getElementById('btnGuardar').disabled = false;
    } else {
        inputDiferencia.style.backgroundColor = '#f8d7da';
        document.getElementById('btnGuardar').disabled = true;
    }
}

function actualizarEventos() {
    document.querySelectorAll('.debe-input, .haber-input').forEach(input => {
        input.removeEventListener('input', calcularTotales);
        input.addEventListener('input', calcularTotales);
    });
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    actualizarEventos();
    calcularTotales();
});
</script>
