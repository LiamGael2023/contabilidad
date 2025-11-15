<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-calendar3"></i> Períodos Contables</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoPeriodo">
                <i class="bi bi-plus-circle"></i> Nuevo Período
            </button>
        </div>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($periodos)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No hay períodos contables creados.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Año</th>
                                    <th>Mes</th>
                                    <th>Período</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($periodos as $per): ?>
                                    <tr>
                                        <td><?= $per['anio'] ?></td>
                                        <td><?= str_pad($per['mes'], 2, '0', STR_PAD_LEFT) ?></td>
                                        <td><strong><?= str_pad($per['mes'], 2, '0', STR_PAD_LEFT) ?>/<?= $per['anio'] ?></strong></td>
                                        <td><?= date('d/m/Y', strtotime($per['fecha_inicio'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($per['fecha_fin'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $per['estado'] === 'abierto' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($per['estado']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($per['estado'] === 'abierto'): ?>
                                                <form method="POST" action="/periodos/cerrar/<?= $per['id'] ?>" style="display: inline;">
                                                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                                    <button type="submit" class="btn btn-sm btn-warning"
                                                            onclick="return confirm('¿Cerrar este período?')">
                                                        <i class="bi bi-lock"></i> Cerrar
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form method="POST" action="/periodos/reabrir/<?= $per['id'] ?>" style="display: inline;">
                                                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                                    <button type="submit" class="btn btn-sm btn-info"
                                                            onclick="return confirm('¿Reabrir este período?')">
                                                        <i class="bi bi-unlock"></i> Reabrir
                                                    </button>
                                                </form>
                                            <?php endif; ?>
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

<!-- Modal Nuevo Período -->
<div class="modal fade" id="modalNuevoPeriodo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/periodos/crear">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Período Contable</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="anio" class="form-label">Año</label>
                        <input type="number" class="form-control" id="anio" name="anio"
                               min="2020" max="2099" value="<?= date('Y') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="mes" class="form-label">Mes</label>
                        <select class="form-select" id="mes" name="mes" required>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= $i == date('n') ? 'selected' : '' ?>>
                                    <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?> -
                                    <?= ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                         'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'][$i] ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Período</button>
                </div>
            </form>
        </div>
    </div>
</div>
