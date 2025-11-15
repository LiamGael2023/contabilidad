<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-receipt"></i> Comprobantes de Pago</h1>
            <a href="/comprobantes/nuevo" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Comprobante
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
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($comprobantes)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No hay comprobantes registrados.
                        <a href="/comprobantes/nuevo" class="alert-link">Crear el primer comprobante</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Serie-Número</th>
                                    <th>Tercero</th>
                                    <th>Operación</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($comprobantes as $comp): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($comp['fecha_emision'])) ?></td>
                                        <td><?= htmlspecialchars($comp['tipo_comprobante']) ?></td>
                                        <td><code><?= $comp['serie'] ?>-<?= $comp['numero'] ?></code></td>
                                        <td><?= htmlspecialchars($comp['tercero']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $comp['tipo_operacion'] === 'venta' ? 'success' : 'info' ?>">
                                                <?= ucfirst($comp['tipo_operacion']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <?= $comp['moneda'] === 'USD' ? '$' : 'S/' ?>
                                            <?= number_format($comp['total'], 2) ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $comp['estado'] === 'emitido' ? 'success' : ($comp['estado'] === 'anulado' ? 'danger' : 'warning') ?>">
                                                <?= ucfirst($comp['estado']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/comprobantes/ver/<?= $comp['id'] ?>"
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
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
