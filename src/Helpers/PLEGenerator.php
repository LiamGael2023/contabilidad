<?php

namespace Helpers;

use Models\AsientoContable;
use Models\DetalleAsiento;
use Models\Empresa;
use Models\ComprobantePago;

/**
 * Generador de archivos PLE (Programa de Libros Electrónicos) para SUNAT
 */
class PLEGenerator
{
    private $separator = '|';
    private $lineEnding = "\r\n";

    /**
     * Generar archivo PLE
     */
    public function generar($empresaId, $periodo, $tipoLibro)
    {
        switch ($tipoLibro) {
            case '050100': // Libro Diario
                return $this->generarLibroDiario($empresaId, $periodo);
            case '060100': // Libro Mayor
                return $this->generarLibroMayor($empresaId, $periodo);
            case '010100': // Caja y Bancos
                return $this->generarCajaBancos($empresaId, $periodo);
            case '080100': // Registro de Compras
                return $this->generarRegistroCompras($empresaId, $periodo);
            case '140100': // Registro de Ventas
                return $this->generarRegistroVentas($empresaId, $periodo);
            default:
                throw new \Exception('Tipo de libro no soportado');
        }
    }

    /**
     * Generar Libro Diario - Formato 5.1
     */
    private function generarLibroDiario($empresaId, $periodo)
    {
        $empresaModel = new Empresa();
        $asientoModel = new AsientoContable();
        $detalleModel = new DetalleAsiento();

        $empresa = $empresaModel->find($empresaId);
        $asientos = $asientoModel->getByPeriodo($periodo['id']);

        // Nombre del archivo según especificación SUNAT
        // LE + RUC + AÑO + MES + DÍA + LIBRO + OPORTUNIDAD + OPERACIÓN + ESTADO + MONEDA + IDENTIFICADOR
        $fecha = $periodo['anio'] . str_pad($periodo['mes'], 2, '0', STR_PAD_LEFT) . '00';
        $nombreArchivo = "LE{$empresa['ruc']}{$fecha}050100001111{$periodo['anio']}{$periodo['mes']}1.txt";

        $contenido = '';
        $totalRegistros = 0;

        foreach ($asientos as $asiento) {
            $detalles = $detalleModel->getByAsiento($asiento['id']);

            foreach ($detalles as $detalle) {
                $linea = [];

                // 1. Período
                $linea[] = $periodo['anio'] . str_pad($periodo['mes'], 2, '0', STR_PAD_LEFT) . '00';

                // 2. Número correlativo del asiento
                $linea[] = str_pad($asiento['numero_asiento'], 10, '0', STR_PAD_LEFT);

                // 3. Código del plan de cuentas utilizado
                $linea[] = '01'; // 01 = PCGE

                // 4. Código de la cuenta contable
                $linea[] = $detalle['codigo'];

                // 5. Fecha de operación
                $linea[] = str_replace('-', '', $asiento['fecha']);

                // 6. Glosa o descripción
                $linea[] = $detalle['glosa'] ?: $asiento['glosa'];

                // 7. Código del libro o registro
                $linea[] = '00';

                // 8. Debe
                $linea[] = number_format($detalle['debe'], 2, '.', '');

                // 9. Haber
                $linea[] = number_format($detalle['haber'], 2, '.', '');

                // 10. Estado de la operación
                $linea[] = $asiento['estado'] === 'anulado' ? '2' : '1';

                $contenido .= implode($this->separator, $linea) . $this->lineEnding;
                $totalRegistros++;
            }
        }

        // Guardar archivo
        $rutaArchivo = $this->guardarArchivo($nombreArchivo, $contenido);

        return $nombreArchivo;
    }

    /**
     * Generar Libro Mayor - Formato 6.1
     */
    private function generarLibroMayor($empresaId, $periodo)
    {
        // Similar al Libro Diario pero agrupado por cuenta
        return $this->generarLibroDiario($empresaId, $periodo);
    }

    /**
     * Generar Libro Caja y Bancos - Formato 1.1
     */
    private function generarCajaBancos($empresaId, $periodo)
    {
        $empresaModel = new Empresa();
        $empresa = $empresaModel->find($empresaId);

        $fecha = $periodo['anio'] . str_pad($periodo['mes'], 2, '0', STR_PAD_LEFT) . '00';
        $nombreArchivo = "LE{$empresa['ruc']}{$fecha}010100001111{$periodo['anio']}{$periodo['mes']}1.txt";

        // Implementar lógica específica de Caja y Bancos
        $contenido = '';

        $rutaArchivo = $this->guardarArchivo($nombreArchivo, $contenido);

        return $nombreArchivo;
    }

    /**
     * Generar Registro de Compras - Formato 8.1
     */
    private function generarRegistroCompras($empresaId, $periodo)
    {
        $empresaModel = new Empresa();
        $comprobanteModel = new ComprobantePago();

        $empresa = $empresaModel->find($empresaId);
        $compras = $comprobanteModel->getCompras($periodo['id']);

        $fecha = $periodo['anio'] . str_pad($periodo['mes'], 2, '0', STR_PAD_LEFT) . '00';
        $nombreArchivo = "LE{$empresa['ruc']}{$fecha}080100001111{$periodo['anio']}{$periodo['mes']}1.txt";

        $contenido = '';

        foreach ($compras as $compra) {
            $linea = [];

            // Implementar campos del Registro de Compras según SUNAT
            $linea[] = $fecha;
            $linea[] = str_pad($compra['numero'], 10, '0', STR_PAD_LEFT);
            // ... más campos según formato SUNAT

            $contenido .= implode($this->separator, $linea) . $this->lineEnding;
        }

        $this->guardarArchivo($nombreArchivo, $contenido);

        return $nombreArchivo;
    }

    /**
     * Generar Registro de Ventas - Formato 14.1
     */
    private function generarRegistroVentas($empresaId, $periodo)
    {
        $empresaModel = new Empresa();
        $comprobanteModel = new ComprobantePago();

        $empresa = $empresaModel->find($empresaId);
        $ventas = $comprobanteModel->getVentas($periodo['id']);

        $fecha = $periodo['anio'] . str_pad($periodo['mes'], 2, '0', STR_PAD_LEFT) . '00';
        $nombreArchivo = "LE{$empresa['ruc']}{$fecha}140100001111{$periodo['anio']}{$periodo['mes']}1.txt";

        $contenido = '';

        foreach ($ventas as $venta) {
            $linea = [];

            // Implementar campos del Registro de Ventas según SUNAT
            $linea[] = $fecha;
            $linea[] = str_pad($venta['numero'], 10, '0', STR_PAD_LEFT);
            // ... más campos según formato SUNAT

            $contenido .= implode($this->separator, $linea) . $this->lineEnding;
        }

        $this->guardarArchivo($nombreArchivo, $contenido);

        return $nombreArchivo;
    }

    /**
     * Guardar archivo en storage
     */
    private function guardarArchivo($nombreArchivo, $contenido)
    {
        $directorio = STORAGE . 'ple' . DS;

        // Crear directorio si no existe
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $rutaArchivo = $directorio . $nombreArchivo;
        file_put_contents($rutaArchivo, $contenido);

        return $rutaArchivo;
    }
}
