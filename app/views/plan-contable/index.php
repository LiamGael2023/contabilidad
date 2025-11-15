<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-list-ol"></i> Plan Contable</h1>
            <div>
                <a href="/plan-contable/crear" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nueva Cuenta
                </a>
            </div>
        </div>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <input type="text" class="form-control" id="buscar" placeholder="Buscar cuenta..." onkeyup="buscarEnTabla('buscar', 'tabla-cuentas')">
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($cuentas)): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        No hay cuentas en el plan contable.
                        <button class="btn btn-sm btn-primary" onclick="cargarPCGE()">
                            Cargar PCGE
                        </button>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="tabla-cuentas">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Elemento</th>
                                    <th>Nivel</th>
                                    <th>Naturaleza</th>
                                    <th>Tipo</th>
                                    <th>Recibe Saldo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cuentas as $cuenta): ?>
                                    <tr>
                                        <td><code><?= htmlspecialchars($cuenta['codigo']) ?></code></td>
                                        <td style="padding-left: <?= ($cuenta['nivel'] - 1) * 15 ?>px;">
                                            <?= htmlspecialchars($cuenta['descripcion']) ?>
                                        </td>
                                        <td><?= $cuenta['elemento'] ?></td>
                                        <td><?= $cuenta['nivel'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $cuenta['naturaleza'] === 'DEUDORA' ? 'primary' : 'success' ?>">
                                                <?= $cuenta['naturaleza'] ?>
                                            </span>
                                        </td>
                                        <td><?= $cuenta['tipo'] ?></td>
                                        <td class="text-center">
                                            <?php if ($cuenta['recibe_saldo']): ?>
                                                <i class="bi bi-check-circle text-success"></i>
                                            <?php else: ?>
                                                <i class="bi bi-x-circle text-muted"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="/plan-contable/editar/<?= $cuenta['id'] ?>"
                                               class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted mt-3">
                        Total de cuentas: <strong><?= count($cuentas) ?></strong>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function cargarPCGE() {
    if (confirm('¿Cargar el Plan Contable General Empresarial?\n\nEsto agregará las cuentas estándar del PCGE.')) {
        fetch('/plan-contable/cargar-pcge')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error al cargar PCGE: ' + error);
            });
    }
}
</script>
