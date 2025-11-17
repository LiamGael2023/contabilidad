<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-calendar me-2"></i>Flujo de Caja Diario
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/movimientos-caja" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="/movimientos-caja/flujo-diario">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="fecha" class="form-control" value="<?= $fecha ?? date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search me-1"></i>Consultar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row row-cards mb-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Total Ingresos</div>
                        <div class="h1 mb-3 text-success">
                            S/ <?= number_format($flujo['total_ingresos'] ?? 0, 2) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Total Egresos</div>
                        <div class="h1 mb-3 text-danger">
                            S/ <?= number_format($flujo['total_egresos'] ?? 0, 2) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader">Saldo Neto</div>
                        <div class="h1 mb-3 <?= ($flujo['saldo_neto'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                            S/ <?= number_format($flujo['saldo_neto'] ?? 0, 2) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Flujo de Caja del <?= date('d/m/Y', strtotime($fecha ?? date('Y-m-d'))) ?>
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i>
                    <strong>Interpretación:</strong><br>
                    Un saldo neto positivo indica que hubo más ingresos que egresos en el día.<br>
                    Un saldo neto negativo indica que hubo más egresos que ingresos.
                </div>
            </div>
        </div>
    </div>
</div>
