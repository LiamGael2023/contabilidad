<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-people"></i> <?= htmlspecialchars($title) ?></h1>
            <a href="/terceros/nuevo" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Tercero
            </a>
        </div>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="btn-group" role="group">
            <a href="/terceros" class="btn btn-outline-primary <?= !$tipo_filtro ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Todos
            </a>
            <a href="/terceros?tipo=cliente" class="btn btn-outline-success <?= $tipo_filtro === 'cliente' ? 'active' : '' ?>">
                <i class="bi bi-person-check"></i> Clientes
            </a>
            <a href="/terceros?tipo=proveedor" class="btn btn-outline-info <?= $tipo_filtro === 'proveedor' ? 'active' : '' ?>">
                <i class="bi bi-truck"></i> Proveedores
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($terceros)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No hay terceros registrados.
                        <a href="/terceros/nuevo" class="alert-link">Registrar el primero</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-vcenter">
                            <thead>
                                <tr>
                                    <th>Tipo Doc.</th>
                                    <th>Número</th>
                                    <th>Razón Social</th>
                                    <th>Nombre Comercial</th>
                                    <th>Tipo</th>
                                    <th>Contacto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($terceros as $tercero): ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $tipos_doc = [
                                                '1' => 'DNI',
                                                '6' => 'RUC',
                                                '4' => 'CE',
                                                '7' => 'PAS',
                                                '0' => 'Otro'
                                            ];
                                            echo htmlspecialchars($tipos_doc[$tercero['tipo_documento']] ?? $tercero['tipo_documento']);
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($tercero['numero_documento']) ?></td>
                                        <td><?= htmlspecialchars($tercero['razon_social']) ?></td>
                                        <td><?= htmlspecialchars($tercero['nombre_comercial'] ?? '-') ?></td>
                                        <td>
                                            <?php if ($tercero['tipo_tercero'] === 'cliente'): ?>
                                                <span class="badge bg-success">Cliente</span>
                                            <?php elseif ($tercero['tipo_tercero'] === 'proveedor'): ?>
                                                <span class="badge bg-info">Proveedor</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary">Ambos</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small>
                                                <?php if ($tercero['telefono']): ?>
                                                    <i class="bi bi-telephone"></i> <?= htmlspecialchars($tercero['telefono']) ?><br>
                                                <?php endif; ?>
                                                <?php if ($tercero['email']): ?>
                                                    <i class="bi bi-envelope"></i> <?= htmlspecialchars($tercero['email']) ?>
                                                <?php endif; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="/terceros/editar/<?= $tercero['id'] ?>"
                                                   class="btn btn-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger"
                                                        onclick="eliminarTercero(<?= $tercero['id'] ?>, '<?= htmlspecialchars($tercero['razon_social']) ?>')"
                                                        title="Eliminar">
                                                    <i class="bi bi-trash"></i>
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

<form id="formEliminar" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
</form>

<script>
function eliminarTercero(id, nombre) {
    if (confirm(`¿Está seguro de eliminar a "${nombre}"?\n\nEsta acción desactivará el tercero pero no eliminará su historial.`)) {
        const form = document.getElementById('formEliminar');
        form.action = `/terceros/eliminar/${id}`;
        form.submit();
    }
}
</script>
