-- ============================================
-- SCRIPT CORREGIDO: Registro de Ventas y Compras
-- Con collation utf8mb4_general_ci para compatibilidad
-- ============================================

-- Eliminar tablas si ya existen (para recrearlas con la collation correcta)
DROP TABLE IF EXISTS `registro_compras`;
DROP TABLE IF EXISTS `registro_ventas`;

-- ====================
-- Tabla: registro_ventas
-- ====================
CREATE TABLE `registro_ventas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `empresa_id` INT NOT NULL,
    `periodo_id` INT NOT NULL,
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
    INDEX `idx_empresa_periodo` (`empresa_id`, `periodo_id`),
    INDEX `idx_fecha_emision` (`fecha_emision`),
    INDEX `idx_tipo_comprobante` (`tipo_comprobante`),
    INDEX `idx_cliente` (`numero_documento_cliente`),
    UNIQUE KEY `unique_comprobante` (`empresa_id`, `tipo_comprobante`, `serie`, `numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Registro de ventas para PLE 14.1 SUNAT';

-- ====================
-- Tabla: registro_compras
-- ====================
CREATE TABLE `registro_compras` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `empresa_id` INT NOT NULL,
    `periodo_id` INT NOT NULL,
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
    INDEX `idx_empresa_periodo` (`empresa_id`, `periodo_id`),
    INDEX `idx_fecha_emision` (`fecha_emision`),
    INDEX `idx_tipo_comprobante` (`tipo_comprobante`),
    INDEX `idx_proveedor` (`numero_documento_proveedor`),
    UNIQUE KEY `unique_comprobante` (`empresa_id`, `tipo_comprobante`, `serie`, `numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Registro de compras para PLE 8.1 SUNAT';

-- ====================
-- Verificación
-- ====================
SELECT
    'Tablas creadas exitosamente con collation utf8mb4_general_ci' as Resultado,
    (SELECT COUNT(*) FROM information_schema.tables
     WHERE table_schema = DATABASE()
     AND table_name = 'registro_ventas') as tabla_ventas_existe,
    (SELECT COUNT(*) FROM information_schema.tables
     WHERE table_schema = DATABASE()
     AND table_name = 'registro_compras') as tabla_compras_existe;

-- Verificar collation de las tablas
SELECT
    table_name,
    table_collation
FROM information_schema.tables
WHERE table_schema = DATABASE()
AND table_name IN ('registro_ventas', 'registro_compras', 'terceros');
