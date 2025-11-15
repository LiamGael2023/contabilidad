-- ============================================
-- SCRIPT: Módulos Financieros Completos
-- Cuentas por Cobrar, Cuentas por Pagar, Caja y Bancos, Tipos de Cambio
-- ============================================

-- ====================
-- TABLA: cuentas_por_cobrar
-- ====================
CREATE TABLE IF NOT EXISTS `cuentas_por_cobrar` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `empresa_id` INT NOT NULL,
    `periodo_id` INT NOT NULL,
    `tercero_id` INT NOT NULL,
    `registro_venta_id` INT NULL COMMENT 'Referencia a registro_ventas si aplica',
    `tipo_documento` VARCHAR(2) NOT NULL COMMENT '01=Factura, 03=Boleta, etc',
    `serie` VARCHAR(4) NOT NULL,
    `numero` VARCHAR(8) NOT NULL,
    `fecha_emision` DATE NOT NULL,
    `fecha_vencimiento` DATE NOT NULL,
    `moneda` CHAR(3) DEFAULT 'PEN',
    `tipo_cambio` DECIMAL(8,4) DEFAULT 1.0000,
    `importe_total` DECIMAL(12,2) NOT NULL,
    `saldo_pendiente` DECIMAL(12,2) NOT NULL,
    `estado` ENUM('pendiente', 'parcial', 'pagado', 'vencido') DEFAULT 'pendiente',
    `observaciones` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_empresa` (`empresa_id`),
    INDEX `idx_tercero` (`tercero_id`),
    INDEX `idx_estado` (`estado`),
    INDEX `idx_vencimiento` (`fecha_vencimiento`),
    UNIQUE KEY `unique_documento` (`empresa_id`, `tipo_documento`, `serie`, `numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Cuentas por cobrar a clientes';

-- ====================
-- TABLA: cuentas_por_pagar
-- ====================
CREATE TABLE IF NOT EXISTS `cuentas_por_pagar` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `empresa_id` INT NOT NULL,
    `periodo_id` INT NOT NULL,
    `tercero_id` INT NOT NULL,
    `registro_compra_id` INT NULL COMMENT 'Referencia a registro_compras si aplica',
    `tipo_documento` VARCHAR(2) NOT NULL,
    `serie` VARCHAR(4) NOT NULL,
    `numero` VARCHAR(8) NOT NULL,
    `fecha_emision` DATE NOT NULL,
    `fecha_vencimiento` DATE NOT NULL,
    `moneda` CHAR(3) DEFAULT 'PEN',
    `tipo_cambio` DECIMAL(8,4) DEFAULT 1.0000,
    `importe_total` DECIMAL(12,2) NOT NULL,
    `saldo_pendiente` DECIMAL(12,2) NOT NULL,
    `estado` ENUM('pendiente', 'parcial', 'pagado', 'vencido') DEFAULT 'pendiente',
    `observaciones` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_empresa` (`empresa_id`),
    INDEX `idx_tercero` (`tercero_id`),
    INDEX `idx_estado` (`estado`),
    INDEX `idx_vencimiento` (`fecha_vencimiento`),
    UNIQUE KEY `unique_documento` (`empresa_id`, `tipo_documento`, `serie`, `numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Cuentas por pagar a proveedores';

-- ====================
-- TABLA: pagos
-- ====================
CREATE TABLE IF NOT EXISTS `pagos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `empresa_id` INT NOT NULL,
    `cuenta_por_cobrar_id` INT NULL COMMENT 'Para pagos recibidos de clientes',
    `cuenta_por_pagar_id` INT NULL COMMENT 'Para pagos efectuados a proveedores',
    `fecha_pago` DATE NOT NULL,
    `monto` DECIMAL(12,2) NOT NULL,
    `metodo_pago` VARCHAR(50) NOT NULL COMMENT 'Efectivo, Transferencia, Cheque, etc',
    `numero_operacion` VARCHAR(50) NULL,
    `banco_id` INT NULL COMMENT 'Cuenta bancaria donde se registró el pago',
    `observaciones` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_empresa` (`empresa_id`),
    INDEX `idx_cxc` (`cuenta_por_cobrar_id`),
    INDEX `idx_cxp` (`cuenta_por_pagar_id`),
    INDEX `idx_fecha` (`fecha_pago`),
    INDEX `idx_banco` (`banco_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Registro de pagos recibidos y efectuados';

-- ====================
-- TABLA: bancos
-- ====================
CREATE TABLE IF NOT EXISTS `bancos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `empresa_id` INT NOT NULL,
    `nombre_banco` VARCHAR(100) NOT NULL COMMENT 'BCP, BBVA, Interbank, etc',
    `tipo_cuenta` ENUM('corriente', 'ahorro', 'caja', 'otro') DEFAULT 'corriente',
    `numero_cuenta` VARCHAR(50) NULL,
    `moneda` CHAR(3) DEFAULT 'PEN',
    `saldo_inicial` DECIMAL(12,2) DEFAULT 0.00,
    `saldo_actual` DECIMAL(12,2) DEFAULT 0.00,
    `activo` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_empresa` (`empresa_id`),
    INDEX `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Cuentas bancarias y cajas de la empresa';

-- ====================
-- TABLA: movimientos_caja
-- ====================
CREATE TABLE IF NOT EXISTS `movimientos_caja` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `empresa_id` INT NOT NULL,
    `banco_id` INT NOT NULL,
    `fecha` DATE NOT NULL,
    `tipo_movimiento` ENUM('ingreso', 'egreso') NOT NULL,
    `tipo_operacion` VARCHAR(50) NOT NULL COMMENT 'Cobranza, Pago, Deposito, Retiro, etc',
    `monto` DECIMAL(12,2) NOT NULL,
    `moneda` CHAR(3) DEFAULT 'PEN',
    `tipo_cambio` DECIMAL(8,4) DEFAULT 1.0000,
    `descripcion` VARCHAR(200) NOT NULL,
    `numero_operacion` VARCHAR(50) NULL,
    `tercero_id` INT NULL,
    `categoria` VARCHAR(50) NULL COMMENT 'Ventas, Compras, Gastos, etc',
    `asiento_id` INT NULL COMMENT 'Asiento contable generado',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_empresa` (`empresa_id`),
    INDEX `idx_banco` (`banco_id`),
    INDEX `idx_fecha` (`fecha`),
    INDEX `idx_tipo` (`tipo_movimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Movimientos de caja y bancos';

-- ====================
-- TABLA: tipos_cambio
-- ====================
CREATE TABLE IF NOT EXISTS `tipos_cambio` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fecha` DATE NOT NULL,
    `moneda` CHAR(3) DEFAULT 'USD',
    `compra` DECIMAL(8,4) NOT NULL,
    `venta` DECIMAL(8,4) NOT NULL,
    `fuente` VARCHAR(50) DEFAULT 'SUNAT' COMMENT 'SUNAT, Manual, SBS',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_fecha_moneda` (`fecha`, `moneda`),
    INDEX `idx_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Tipos de cambio SUNAT por día';

-- ====================
-- Insertar algunos registros de ejemplo
-- ====================

-- Tipos de cambio de ejemplo (últimos días)
INSERT IGNORE INTO `tipos_cambio` (`fecha`, `moneda`, `compra`, `venta`, `fuente`) VALUES
('2025-01-13', 'USD', 3.7200, 3.7250, 'SUNAT'),
('2025-01-14', 'USD', 3.7180, 3.7230, 'SUNAT'),
('2025-01-15', 'USD', 3.7150, 3.7200, 'SUNAT');

-- ====================
-- Verificación
-- ====================
SELECT
    'Tablas de módulos financieros creadas exitosamente' as Resultado,
    (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'cuentas_por_cobrar') as cxc_existe,
    (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'cuentas_por_pagar') as cxp_existe,
    (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'pagos') as pagos_existe,
    (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'bancos') as bancos_existe,
    (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'movimientos_caja') as movimientos_existe,
    (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'tipos_cambio') as tipo_cambio_existe;

-- ====================
-- NOTAS IMPORTANTES
-- ====================
-- 1. Las tablas están listas para integrarse con los módulos existentes
-- 2. Los índices están optimizados para consultas rápidas
-- 3. El campo UNIQUE evita duplicados
-- 4. Soporte completo para multi-moneda
-- 5. Trazabilidad completa con created_at y updated_at
-- ====================
