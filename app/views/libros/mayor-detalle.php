<div class="row no-print">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-journal-bookmark"></i> Libro Mayor</h1>
            <div>
                <a href="/libros/mayor" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <button onclick="window.print()" class="btn btn-secondary">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5>
                    <code><?= htmlspecialchars($cuenta['codigo']) ?></code>
                    <?= htmlspecialchars($cuenta['descripcion']) ?>
                </h5>
                <p class="mb-0">
                    <span class="badge bg-<?= $cuenta['naturaleza'] === 'DEUDORA' ? 'primary' : 'success' ?>">
                        <?= $cuenta['naturaleza'] ?>
                    </span>
                    <span class="badge bg-secondary"><?= $cuenta['tipo'] ?></span>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($movimientos)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No hay movimientos en esta cuenta.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Asiento</th>
                                    <th>Glosa</th>
                                    <th class="text-end">Debe</th>
                                    <th class="text-end">Haber</th>
                                    <th class="text-end">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($movimientos as $mov): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($mov['fecha'])) ?></td>
                                        <td><?= str_pad($mov['numero_asiento'], 6, '0', STR_PAD_LEFT) ?></td>
                                        <td><?= htmlspecialchars($mov['glosa'] ?: $mov['glosa_asiento']) ?></td>
                                        <td class="text-end">
                                            <?= $mov['debe'] > 0 ? number_format($mov['debe'], 2) : '-' ?>
                                        </td>
                                        <td class="text-end">
                                            <?= $mov['haber'] > 0 ? number_format($mov['haber'], 2) : '-' ?>
                                        </td>
                                        <td class="text-end fw-bold">
                                            <?= number_format($mov['saldo'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="fw-bold table-light">
                                <tr>
                                    <td colspan="3" class="text-end">SALDO FINAL:</td>
                                    <td class="text-end">
                                        <?= number_format(array_sum(array_column($movimientos, 'debe')), 2) ?>
                                    </td>
                                    <td class="text-end">
                                        <?= number_format(array_sum(array_column($movimientos, 'haber')), 2) ?>
                                    </td>
                                    <td class="text-end">
                                        <?= number_format(end($movimientos)['saldo'], 2) ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
