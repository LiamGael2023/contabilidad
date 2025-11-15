<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-building"></i> Empresas</h1>
            <a href="/empresas/nueva" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Empresa
            </a>
        </div>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($empresas)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No hay empresas registradas.
                        <a href="/empresas/nueva" class="alert-link">Crear la primera empresa</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>RUC</th>
                                    <th>Razón Social</th>
                                    <th>Nombre Comercial</th>
                                    <th>Régimen</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($empresas as $empresa): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($empresa['ruc']) ?></td>
                                        <td><?= htmlspecialchars($empresa['razon_social']) ?></td>
                                        <td><?= htmlspecialchars($empresa['nombre_comercial'] ?? '-') ?></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?= htmlspecialchars($empresa['regimen_tributario']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($empresa['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="/empresas/editar/<?= $empresa['id'] ?>"
                                                   class="btn btn-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-info"
                                                        onclick="seleccionarEmpresa(<?= $empresa['id'] ?>, '<?= htmlspecialchars($empresa['razon_social']) ?>')"
                                                        title="Seleccionar">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function seleccionarEmpresa(id, nombre) {
    if (confirm(`¿Seleccionar empresa "${nombre}"?`)) {
        // Aquí se implementaría la lógica para guardar en sesión
        alert('Funcionalidad de selección de empresa - Por implementar');
    }
}
</script>
