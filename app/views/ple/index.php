<div class="row">
    <div class="col-12">
        <h1><i class="bi bi-file-earmark-text"></i> PLE - Programa de Libros Electrónicos</h1>
        <hr>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="alert alert-info">
            <h5><i class="bi bi-info-circle"></i> Información</h5>
            <p class="mb-0">
                El PLE (Programa de Libros Electrónicos) genera archivos en formato TXT
                según las especificaciones de SUNAT para la presentación de libros contables electrónicos.
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Generar Archivo PLE</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/ple/generar">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <div class="mb-3">
                        <label for="periodo_id" class="form-label">Período *</label>
                        <select class="form-select" id="periodo_id" name="periodo_id" required>
                            <option value="">Seleccione un período...</option>
                            <?php foreach ($periodos as $per): ?>
                                <option value="<?= $per['id'] ?>">
                                    <?= sprintf('%02d', $per['mes']) ?>/<?= $per['anio'] ?>
                                    - <?= ucfirst($per['estado']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tipo_libro" class="form-label">Tipo de Libro *</label>
                        <select class="form-select" id="tipo_libro" name="tipo_libro" required>
                            <option value="">Seleccione tipo de libro...</option>
                            <option value="050100">5.1 - Libro Diario</option>
                            <option value="050200">5.2 - Libro Diario Simplificado</option>
                            <option value="060100">6.1 - Libro Mayor</option>
                            <option value="010100">1.1 - Libro Caja y Bancos</option>
                            <option value="080100">8.1 - Registro de Compras</option>
                            <option value="140100">14.1 - Registro de Ventas</option>
                        </select>
                        <small class="text-muted">
                            Seleccione el formato según el libro que desea generar
                        </small>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-file-earmark-arrow-down"></i> Generar Archivo PLE
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Estructura del Nombre de Archivo</h6>
            </div>
            <div class="card-body">
                <p>Los archivos PLE se generan con el siguiente formato:</p>
                <code>LE + RUC + AAAAMM00 + LIBRO + 00 + 1 + 1 + 1 + 1.txt</code>
                <hr>
                <p class="mb-0"><small>
                    <strong>Ejemplo:</strong><br>
                    LE20123456789202401000501000011111.txt<br>
                    RUC: 20123456789 | Período: 2024-01 | Libro: 5.1 (Diario)
                </small></p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">Tipos de Libro PLE</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>1.1</strong> - Libro Caja y Bancos
                    </li>
                    <li class="mb-2">
                        <strong>5.1</strong> - Libro Diario
                    </li>
                    <li class="mb-2">
                        <strong>5.2</strong> - Libro Diario Simplificado
                    </li>
                    <li class="mb-2">
                        <strong>6.1</strong> - Libro Mayor
                    </li>
                    <li class="mb-2">
                        <strong>8.1</strong> - Registro de Compras
                    </li>
                    <li class="mb-2">
                        <strong>14.1</strong> - Registro de Ventas
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">Notas Importantes</h6>
            </div>
            <div class="card-body">
                <ul class="small">
                    <li>Los archivos se generan en formato TXT</li>
                    <li>Separador: | (pipe)</li>
                    <li>Codificación: UTF-8</li>
                    <li>Los archivos se guardan en storage/ple/</li>
                    <li>Verificar el período esté cerrado antes de enviar a SUNAT</li>
                </ul>
            </div>
        </div>
    </div>
</div>
