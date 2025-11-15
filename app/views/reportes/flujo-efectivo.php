<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-cash-stack"></i> Estado de Flujo de Efectivo</h1>
            <div>
                <button class="btn btn-success" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
                <button class="btn btn-primary" onclick="exportarExcel()">
                    <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                </button>
            </div>
        </div>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Encabezado del Reporte -->
                <div class="text-center mb-4">
                    <h3><?= htmlspecialchars($_SESSION['empresa_nombre'] ?? 'Empresa') ?></h3>
                    <h5>Estado de Flujo de Efectivo</h5>
                    <p class="text-muted">
                        Período: <?= htmlspecialchars($periodo['mes']) ?>/<?= htmlspecialchars($periodo['anio']) ?>
                    </p>
                </div>

                <!-- Actividades de Operación -->
                <h5 class="bg-light p-2 mb-3">
                    <i class="bi bi-arrow-repeat"></i> Actividades de Operación
                </h5>
                <div class="table-responsive mb-4">
                    <table class="table table-sm">
                        <tbody>
                            <?php
                            $totalOperacion = 0;
                            if (isset($flujo['operacion']) && is_array($flujo['operacion'])):
                                foreach ($flujo['operacion'] as $item):
                                    $monto = floatval($item['monto'] ?? 0);
                                    $totalOperacion += $monto;
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['concepto'] ?? '') ?></td>
                                    <td class="text-end" width="20%">
                                        <?= number_format($monto, 2) ?>
                                    </td>
                                </tr>
                            <?php
                                endforeach;
                            else:
                            ?>
                                <tr>
                                    <td>Cobro por ventas de bienes y servicios</td>
                                    <td class="text-end" width="20%">0.00</td>
                                </tr>
                                <tr>
                                    <td>Pago a proveedores de bienes y servicios</td>
                                    <td class="text-end" width="20%">0.00</td>
                                </tr>
                                <tr>
                                    <td>Pago de remuneraciones y beneficios sociales</td>
                                    <td class="text-end" width="20%">0.00</td>
                                </tr>
                                <tr>
                                    <td>Pago de tributos</td>
                                    <td class="text-end" width="20%">0.00</td>
                                </tr>
                            <?php endif; ?>
                            <tr class="fw-bold bg-light">
                                <td>Efectivo neto de actividades de operación</td>
                                <td class="text-end"><?= number_format($totalOperacion, 2) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Actividades de Inversión -->
                <h5 class="bg-light p-2 mb-3">
                    <i class="bi bi-graph-up-arrow"></i> Actividades de Inversión
                </h5>
                <div class="table-responsive mb-4">
                    <table class="table table-sm">
                        <tbody>
                            <?php
                            $totalInversion = 0;
                            if (isset($flujo['inversion']) && is_array($flujo['inversion'])):
                                foreach ($flujo['inversion'] as $item):
                                    $monto = floatval($item['monto'] ?? 0);
                                    $totalInversion += $monto;
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['concepto'] ?? '') ?></td>
                                    <td class="text-end" width="20%">
                                        <?= number_format($monto, 2) ?>
                                    </td>
                                </tr>
                            <?php
                                endforeach;
                            else:
                            ?>
                                <tr>
                                    <td>Adquisición de inmuebles, maquinaria y equipo</td>
                                    <td class="text-end" width="20%">0.00</td>
                                </tr>
                                <tr>
                                    <td>Venta de inmuebles, maquinaria y equipo</td>
                                    <td class="text-end" width="20%">0.00</td>
                                </tr>
                            <?php endif; ?>
                            <tr class="fw-bold bg-light">
                                <td>Efectivo neto de actividades de inversión</td>
                                <td class="text-end"><?= number_format($totalInversion, 2) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Actividades de Financiamiento -->
                <h5 class="bg-light p-2 mb-3">
                    <i class="bi bi-bank"></i> Actividades de Financiamiento
                </h5>
                <div class="table-responsive mb-4">
                    <table class="table table-sm">
                        <tbody>
                            <?php
                            $totalFinanciamiento = 0;
                            if (isset($flujo['financiamiento']) && is_array($flujo['financiamiento'])):
                                foreach ($flujo['financiamiento'] as $item):
                                    $monto = floatval($item['monto'] ?? 0);
                                    $totalFinanciamiento += $monto;
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['concepto'] ?? '') ?></td>
                                    <td class="text-end" width="20%">
                                        <?= number_format($monto, 2) ?>
                                    </td>
                                </tr>
                            <?php
                                endforeach;
                            else:
                            ?>
                                <tr>
                                    <td>Obtención de préstamos</td>
                                    <td class="text-end" width="20%">0.00</td>
                                </tr>
                                <tr>
                                    <td>Pago de préstamos</td>
                                    <td class="text-end" width="20%">0.00</td>
                                </tr>
                                <tr>
                                    <td>Aportes de capital</td>
                                    <td class="text-end" width="20%">0.00</td>
                                </tr>
                                <tr>
                                    <td>Pago de dividendos</td>
                                    <td class="text-end" width="20%">0.00</td>
                                </tr>
                            <?php endif; ?>
                            <tr class="fw-bold bg-light">
                                <td>Efectivo neto de actividades de financiamiento</td>
                                <td class="text-end"><?= number_format($totalFinanciamiento, 2) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Resumen -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="fw-bold">
                                <td>Aumento (Disminución) Neto de Efectivo</td>
                                <td class="text-end" width="20%">
                                    <?php
                                    $aumentoNeto = $totalOperacion + $totalInversion + $totalFinanciamiento;
                                    ?>
                                    <?= number_format($aumentoNeto, 2) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Saldo de Efectivo al Inicio del Período</td>
                                <td class="text-end">
                                    <?= number_format($flujo['saldo_inicial'] ?? 0, 2) ?>
                                </td>
                            </tr>
                            <tr class="table-primary fw-bold">
                                <td>Saldo de Efectivo al Final del Período</td>
                                <td class="text-end">
                                    <?php
                                    $saldoInicial = floatval($flujo['saldo_inicial'] ?? 0);
                                    $saldoFinal = $saldoInicial + $aumentoNeto;
                                    ?>
                                    <?= number_format($saldoFinal, 2) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle"></i>
                    <strong>Nota:</strong> El Estado de Flujo de Efectivo muestra los movimientos de efectivo
                    clasificados en actividades de operación, inversión y financiamiento, según la
                    NIC 7 adoptada en Perú.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportarExcel() {
    alert('Función de exportación a Excel - Por implementar');
    // Aquí se implementaría la exportación a Excel
}
</script>
