<div class="row no-print">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-journal-bookmark"></i> Libro Mayor</h1>
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="bi bi-printer"></i> Imprimir
            </button>
        </div>
        <hr>
    </div>
</div>

<div class="row mb-3 no-print">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6>Período</h6>
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
                <?php if (empty($cuentas)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No hay movimientos registrados.
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($cuentas as $cuenta): ?>
                            <a href="/libros/mayor/<?= $cuenta['id'] ?>"
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($cuenta['codigo']) ?></strong>
                                        <?= htmlspecialchars($cuenta['descripcion']) ?>
                                    </div>
                                    <span class="badge bg-primary">
                                        Ver detalle <i class="bi bi-arrow-right"></i>
                                    </span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
