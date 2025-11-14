// Sistema de Contabilidad Perú - JavaScript principal

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts después de 5 segundos
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Confirmación de eliminaciones
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro de eliminar este registro?')) {
                e.preventDefault();
            }
        });
    });

    // Formatear montos como currency
    const moneyInputs = document.querySelectorAll('.money-input');
    moneyInputs.forEach(input => {
        input.addEventListener('blur', function() {
            let value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = value.toFixed(2);
            }
        });
    });

    // Validar balanceo en asientos contables
    const asientoForm = document.getElementById('asiento-form');
    if (asientoForm) {
        asientoForm.addEventListener('submit', function(e) {
            const totalDebe = calcularTotalDebe();
            const totalHaber = calcularTotalHaber();

            if (Math.abs(totalDebe - totalHaber) > 0.01) {
                e.preventDefault();
                alert(`El asiento no está balanceado.\nDebe: ${totalDebe.toFixed(2)}\nHaber: ${totalHaber.toFixed(2)}`);
            }
        });

        // Actualizar totales en tiempo real
        const debeInputs = document.querySelectorAll('input[name="debe[]"]');
        const haberInputs = document.querySelectorAll('input[name="haber[]"]');

        debeInputs.forEach(input => {
            input.addEventListener('input', actualizarTotales);
        });

        haberInputs.forEach(input => {
            input.addEventListener('input', actualizarTotales);
        });
    }
});

// Calcular total debe
function calcularTotalDebe() {
    let total = 0;
    document.querySelectorAll('input[name="debe[]"]').forEach(input => {
        const value = parseFloat(input.value) || 0;
        total += value;
    });
    return total;
}

// Calcular total haber
function calcularTotalHaber() {
    let total = 0;
    document.querySelectorAll('input[name="haber[]"]').forEach(input => {
        const value = parseFloat(input.value) || 0;
        total += value;
    });
    return total;
}

// Actualizar totales
function actualizarTotales() {
    const totalDebe = calcularTotalDebe();
    const totalHaber = calcularTotalHaber();
    const diferencia = totalDebe - totalHaber;

    const totalDebeEl = document.getElementById('total-debe');
    const totalHaberEl = document.getElementById('total-haber');
    const diferenciaEl = document.getElementById('diferencia');

    if (totalDebeEl) totalDebeEl.textContent = totalDebe.toFixed(2);
    if (totalHaberEl) totalHaberEl.textContent = totalHaber.toFixed(2);

    if (diferenciaEl) {
        diferenciaEl.textContent = Math.abs(diferencia).toFixed(2);
        if (Math.abs(diferencia) < 0.01) {
            diferenciaEl.className = 'badge bg-success';
            diferenciaEl.textContent = 'Balanceado';
        } else {
            diferenciaEl.className = 'badge bg-danger';
            diferenciaEl.textContent = 'Diferencia: ' + diferencia.toFixed(2);
        }
    }
}

// Agregar línea a asiento contable
function agregarLinea() {
    const container = document.getElementById('detalle-asiento');
    if (!container) return;

    const index = container.children.length;
    const html = `
        <div class="row mb-2 detalle-linea">
            <div class="col-md-4">
                <select class="form-select" name="cuenta_id[]" required>
                    <option value="">Seleccione cuenta...</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="glosa_detalle[]" placeholder="Glosa">
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" class="form-control money-input" name="debe[]" value="0.00">
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" class="form-control money-input" name="haber[]" value="0.00">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarLinea(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

// Eliminar línea de asiento
function eliminarLinea(button) {
    const row = button.closest('.detalle-linea');
    if (row) {
        row.remove();
        actualizarTotales();
    }
}

// Formatear número como moneda peruana
function formatoPEN(valor) {
    return 'S/ ' + parseFloat(valor).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

// Imprimir reporte
function imprimirReporte() {
    window.print();
}

// Exportar a Excel (simulado - requiere librería)
function exportarExcel(tableId, filename = 'reporte.xlsx') {
    alert('Función de exportación a Excel no implementada aún.');
}

// Buscar en tabla
function buscarEnTabla(inputId, tableId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toUpperCase();
    const table = document.getElementById(tableId);
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        let visible = false;
        const td = tr[i].getElementsByTagName('td');

        for (let j = 0; j < td.length; j++) {
            if (td[j]) {
                const txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    visible = true;
                    break;
                }
            }
        }

        tr[i].style.display = visible ? '' : 'none';
    }
}
