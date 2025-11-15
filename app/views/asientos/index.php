<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-journal-text"></i> Asientos Contables</h1>
            <a href="/asientos/nuevo" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Asiento
            </a>
        </div>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6>Período Actual</h6>
                <p class="mb-0">
                    <strong>
                        <?= sprintf('%02d', $periodo['mes']) ?>/<?= $periodo['anio'] ?>
                    </strong>
                    <span class="badge bg-<?= $periodo['estado'] === 'abierto' ? 'success' : 'secondary' ?>">
                        <?= ucfirst($periodo['estado']) ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($asientos)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No hay asientos registrados en este período.
                        <a href="/asientos/nuevo" class="alert-link">Crear el primer asiento</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Glosa</th>
                                    <th>Comprobante</th>
                                    <th>Debe</th>
                                    <th>Haber</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($asientos as $asiento): ?>
                                    <tr>
                                        <td><?= str_pad($asiento['numero_asiento'], 6, '0', STR_PAD_LEFT) ?></td>
                                        <td><?= date('d/m/Y', strtotime($asiento['fecha'])) ?></td>
                                        <td><?= htmlspecialchars(substr($asiento['glosa'], 0, 50)) ?>...</td>
                                        <td>
                                            <?php if ($asiento['serie_comprobante']): ?>
                                                <?= htmlspecialchars($asiento['tipo_comprobante']) ?>
                                                <?= htmlspecialchars($asiento['serie_comprobante']) ?>-<?= htmlspecialchars($asiento['numero_comprobante']) ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">S/ <?= number_format($asiento['total_debe'], 2) ?></td>
                                        <td class="text-end">S/ <?= number_format($asiento['total_haber'], 2) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $asiento['estado'] === 'registrado' ? 'success' : ($asiento['estado'] === 'anulado' ? 'danger' : 'warning') ?>">
                                                <?= ucfirst($asiento['estado']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="/asientos/ver/<?= $asiento['id'] ?>"
                                                   class="btn btn-info" title="Ver">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <?php if ($asiento['estado'] === 'registrado'): ?>
                                                    <button type="button"
                                                            class="btn btn-danger"
                                                            onclick="anularAsiento(<?= $asiento['id'] ?>)"
                                                            title="Anular">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="4" class="text-end">TOTALES:</td>
                                    <td class="text-end">
                                        S/ <?= number_format(array_sum(array_column($asientos, 'total_debe')), 2) ?>
                                    </td>
                                    <td class="text-end">
                                        S/ <?= number_format(array_sum(array_column($asientos, 'total_haber')), 2) ?>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function anularAsiento(id) {
    const motivo = prompt('Ingrese el motivo de anulación:');
    if (motivo && motivo.trim()) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/asientos/anular/${id}`;

        const motivoInput = document.createElement('input');
        motivoInput.type = 'hidden';
        motivoInput.name = 'motivo';
        motivoInput.value = motivo;

        form.appendChild(motivoInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
