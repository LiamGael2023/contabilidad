-- ============================================
-- SCRIPT DE MIGRACIÓN: Registro de Ventas y Compras
-- Para ejecutar en phpMyAdmin o línea de comandos MySQL
-- ============================================

USE contabilidad_peru;

-- Tabla: registro_ventas
-- Almacena las ventas para generar el PLE 14.1 SUNAT
CREATE TABLE IF NOT EXISTS `registro_ventas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `empresa_id` INT UNSIGNED NOT NULL,
    `periodo_id` INT UNSIGNED NOT NULL,
    `correlativo` INT NOT NULL,
    `fecha_emision` DATE NOT NULL,
    `fecha_vencimiento` DATE NULL,
    `tipo_comprobante` VARCHAR(2) NOT NULL COMMENT '01=Factura, 03=Boleta, 07=NC, 08=ND',
    `serie` VARCHAR(4) NOT NULL,
    `numero` VARCHAR(8) NOT NULL,
    `tipo_documento_cliente` CHAR(1) NOT NULL COMMENT '1=DNI, 6=RUC',
    `numero_documento_cliente` VARCHAR(20) NOT NULL,
    `razon_social_cliente` VARCHAR(200) NOT NULL,
    `valor_exportacion` DECIMAL(12,2) DEFAULT 0.00,
    `base_imponible` DECIMAL(12,2) DEFAULT 0.00,
    `igv` DECIMAL(12,2) DEFAULT 0.00,
    `exonerado` DECIMAL(12,2) DEFAULT 0.00,
    `inafecto` DECIMAL(12,2) DEFAULT 0.00,
    `isc` DECIMAL(12,2) DEFAULT 0.00,
    `otros_tributos` DECIMAL(12,2) DEFAULT 0.00,
    `importe_total` DECIMAL(12,2) NOT NULL,
    `tipo_cambio` DECIMAL(8,4) DEFAULT 1.0000,
    `moneda` CHAR(3) DEFAULT 'PEN' COMMENT 'PEN, USD, EUR',
    `fecha_emision_modificado` DATE NULL COMMENT 'Para N/C y N/D',
    `tipo_comprobante_modificado` VARCHAR(2) NULL,
    `serie_modificado` VARCHAR(4) NULL,
    `numero_modificado` VARCHAR(8) NULL,
    `estado` ENUM('registrado', 'anulado') DEFAULT 'registrado',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`empresa_id`) REFERENCES `empresas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`periodo_id`) REFERENCES `periodos_contables`(`id`) ON DELETE CASCADE,
    INDEX `idx_empresa_periodo` (`empresa_id`, `periodo_id`),
    INDEX `idx_fecha_emision` (`fecha_emision`),
    INDEX `idx_tipo_comprobante` (`tipo_comprobante`),
    UNIQUE KEY `unique_comprobante` (`empresa_id`, `tipo_comprobante`, `serie`, `numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Registro de ventas para PLE 14.1 SUNAT';

-- Tabla: registro_compras
-- Almacena las compras para generar el PLE 8.1 SUNAT
CREATE TABLE IF NOT EXISTS `registro_compras` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `empresa_id` INT UNSIGNED NOT NULL,
    `periodo_id` INT UNSIGNED NOT NULL,
    `correlativo` INT NOT NULL,
    `fecha_emision` DATE NOT NULL,
    `fecha_vencimiento` DATE NULL,
    `tipo_comprobante` VARCHAR(2) NOT NULL COMMENT '01=Factura, 03=Boleta, 07=NC, 08=ND',
    `serie` VARCHAR(4) NOT NULL,
    `numero` VARCHAR(8) NOT NULL,
    `tipo_documento_proveedor` CHAR(1) NOT NULL COMMENT '1=DNI, 6=RUC',
    `numero_documento_proveedor` VARCHAR(20) NOT NULL,
    `razon_social_proveedor` VARCHAR(200) NOT NULL,
    `base_imponible` DECIMAL(12,2) DEFAULT 0.00,
    `igv` DECIMAL(12,2) DEFAULT 0.00 COMMENT 'Crédito fiscal',
    `exonerado` DECIMAL(12,2) DEFAULT 0.00,
    `inafecto` DECIMAL(12,2) DEFAULT 0.00,
    `isc` DECIMAL(12,2) DEFAULT 0.00,
    `otros_tributos` DECIMAL(12,2) DEFAULT 0.00,
    `importe_total` DECIMAL(12,2) NOT NULL,
    `tipo_cambio` DECIMAL(8,4) DEFAULT 1.0000,
    `moneda` CHAR(3) DEFAULT 'PEN' COMMENT 'PEN, USD, EUR',
    `fecha_emision_modificado` DATE NULL COMMENT 'Para N/C y N/D',
    `tipo_comprobante_modificado` VARCHAR(2) NULL,
    `serie_modificado` VARCHAR(4) NULL,
    `numero_modificado` VARCHAR(8) NULL,
    `tipo_compra` ENUM('bien', 'servicio') DEFAULT 'bien',
    `estado` ENUM('registrado', 'anulado') DEFAULT 'registrado',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`empresa_id`) REFERENCES `empresas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`periodo_id`) REFERENCES `periodos_contables`(`id`) ON DELETE CASCADE,
    INDEX `idx_empresa_periodo` (`empresa_id`, `periodo_id`),
    INDEX `idx_fecha_emision` (`fecha_emision`),
    INDEX `idx_tipo_comprobante` (`tipo_comprobante`),
    UNIQUE KEY `unique_comprobante` (`empresa_id`, `tipo_comprobante`, `serie`, `numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Registro de compras para PLE 8.1 SUNAT';

-- Mensaje de confirmación
SELECT 'Tablas registro_ventas y registro_compras creadas exitosamente' AS Resultado;
