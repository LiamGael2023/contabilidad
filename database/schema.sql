-- ============================================
-- Sistema de Contabilidad para Perú
-- Base de Datos MySQL
-- ============================================

CREATE DATABASE IF NOT EXISTS contabilidad CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE contabilidad;

-- ============================================
-- Tabla: usuarios
-- ============================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    rol ENUM('admin', 'contador', 'asistente', 'consultor') DEFAULT 'asistente',
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: empresas
-- ============================================
CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ruc VARCHAR(11) NOT NULL UNIQUE,
    razon_social VARCHAR(200) NOT NULL,
    nombre_comercial VARCHAR(200),
    direccion VARCHAR(255),
    ubigeo VARCHAR(6),
    departamento VARCHAR(50),
    provincia VARCHAR(50),
    distrito VARCHAR(50),
    telefono VARCHAR(20),
    email VARCHAR(100),
    actividad_economica VARCHAR(255),
    regimen_tributario ENUM('RER', 'RG', 'MYPE', 'NRUS') DEFAULT 'RG',
    tipo_contribuyente ENUM('PERSONA_NATURAL', 'PERSONA_JURIDICA') DEFAULT 'PERSONA_JURIDICA',
    fecha_inicio_actividades DATE,
    representante_legal VARCHAR(100),
    dni_representante VARCHAR(8),
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_ruc (ruc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: periodos_contables
-- ============================================
CREATE TABLE periodos_contables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    anio YEAR NOT NULL,
    mes TINYINT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado ENUM('abierto', 'cerrado') DEFAULT 'abierto',
    cerrado_por INT,
    fecha_cierre TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    FOREIGN KEY (cerrado_por) REFERENCES usuarios(id),
    UNIQUE KEY unique_periodo (empresa_id, anio, mes),
    INDEX idx_empresa_periodo (empresa_id, anio, mes)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: plan_contable (PCGE)
-- ============================================
CREATE TABLE plan_contable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    codigo VARCHAR(20) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    elemento TINYINT NOT NULL COMMENT '1-9: Elementos del PCGE',
    nivel TINYINT NOT NULL COMMENT 'Nivel jerárquico: 1=Elemento, 2=Rubro, 3=Cuenta, 4=Subcuenta, 5=Divisionaria',
    codigo_padre VARCHAR(20),
    naturaleza ENUM('DEUDORA', 'ACREEDORA') NOT NULL,
    tipo ENUM('ACTIVO', 'PASIVO', 'PATRIMONIO', 'INGRESO', 'GASTO', 'RESULTADO', 'ORDEN') NOT NULL,
    recibe_saldo BOOLEAN DEFAULT TRUE,
    requiere_auxiliar BOOLEAN DEFAULT FALSE,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cuenta (empresa_id, codigo),
    INDEX idx_empresa_codigo (empresa_id, codigo),
    INDEX idx_elemento (elemento),
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: tipos_comprobante
-- ============================================
CREATE TABLE tipos_comprobante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(2) NOT NULL UNIQUE,
    descripcion VARCHAR(100) NOT NULL,
    abreviatura VARCHAR(10),
    sunat_codigo VARCHAR(2) COMMENT 'Código SUNAT para PLE',
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_codigo (codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: asientos_contables
-- ============================================
CREATE TABLE asientos_contables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    periodo_id INT NOT NULL,
    numero_asiento INT NOT NULL,
    fecha DATE NOT NULL,
    tipo_comprobante_id INT,
    serie_comprobante VARCHAR(10),
    numero_comprobante VARCHAR(20),
    glosa TEXT NOT NULL,
    tipo_cambio DECIMAL(10, 4) DEFAULT 1.0000,
    total_debe DECIMAL(15, 2) DEFAULT 0.00,
    total_haber DECIMAL(15, 2) DEFAULT 0.00,
    estado ENUM('borrador', 'registrado', 'anulado') DEFAULT 'registrado',
    usuario_id INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    anulado_por INT,
    fecha_anulacion TIMESTAMP NULL,
    motivo_anulacion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    FOREIGN KEY (periodo_id) REFERENCES periodos_contables(id) ON DELETE CASCADE,
    FOREIGN KEY (tipo_comprobante_id) REFERENCES tipos_comprobante(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (anulado_por) REFERENCES usuarios(id),
    UNIQUE KEY unique_asiento (empresa_id, periodo_id, numero_asiento),
    INDEX idx_empresa_fecha (empresa_id, fecha),
    INDEX idx_periodo (periodo_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: detalle_asientos (Partida doble)
-- ============================================
CREATE TABLE detalle_asientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asiento_id INT NOT NULL,
    numero_linea INT NOT NULL,
    cuenta_id INT NOT NULL,
    codigo_auxiliar VARCHAR(50) COMMENT 'Código de auxiliar (cliente, proveedor, empleado, etc)',
    tipo_documento VARCHAR(2) COMMENT 'Tipo de documento de identidad',
    numero_documento VARCHAR(20) COMMENT 'Número de documento de identidad',
    glosa VARCHAR(255),
    debe DECIMAL(15, 2) DEFAULT 0.00,
    haber DECIMAL(15, 2) DEFAULT 0.00,
    debe_me DECIMAL(15, 2) DEFAULT 0.00 COMMENT 'Debe en moneda extranjera',
    haber_me DECIMAL(15, 2) DEFAULT 0.00 COMMENT 'Haber en moneda extranjera',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (asiento_id) REFERENCES asientos_contables(id) ON DELETE CASCADE,
    FOREIGN KEY (cuenta_id) REFERENCES plan_contable(id),
    INDEX idx_asiento (asiento_id),
    INDEX idx_cuenta (cuenta_id),
    INDEX idx_numero_linea (asiento_id, numero_linea)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: terceros (Clientes, proveedores, etc)
-- ============================================
CREATE TABLE terceros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    tipo_documento VARCHAR(2) NOT NULL COMMENT '1=DNI, 6=RUC, 4=CE, 7=Pasaporte',
    numero_documento VARCHAR(20) NOT NULL,
    razon_social VARCHAR(200) NOT NULL,
    nombre_comercial VARCHAR(200),
    direccion VARCHAR(255),
    ubigeo VARCHAR(6),
    telefono VARCHAR(20),
    email VARCHAR(100),
    tipo_tercero ENUM('cliente', 'proveedor', 'ambos') DEFAULT 'cliente',
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_tercero (empresa_id, tipo_documento, numero_documento),
    INDEX idx_empresa_documento (empresa_id, numero_documento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: comprobantes_pago
-- ============================================
CREATE TABLE comprobantes_pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    periodo_id INT NOT NULL,
    tipo_comprobante_id INT NOT NULL,
    serie VARCHAR(10) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    fecha_emision DATE NOT NULL,
    fecha_vencimiento DATE,
    tercero_id INT NOT NULL,
    moneda ENUM('PEN', 'USD') DEFAULT 'PEN',
    tipo_cambio DECIMAL(10, 4) DEFAULT 1.0000,
    base_imponible DECIMAL(15, 2) DEFAULT 0.00,
    igv DECIMAL(15, 2) DEFAULT 0.00,
    total DECIMAL(15, 2) NOT NULL,
    detraccion DECIMAL(15, 2) DEFAULT 0.00,
    retencion DECIMAL(15, 2) DEFAULT 0.00,
    percepcion DECIMAL(15, 2) DEFAULT 0.00,
    estado ENUM('emitido', 'pagado', 'anulado', 'pendiente') DEFAULT 'emitido',
    asiento_id INT COMMENT 'Asiento contable generado',
    tipo_operacion ENUM('venta', 'compra') NOT NULL,
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    FOREIGN KEY (periodo_id) REFERENCES periodos_contables(id) ON DELETE CASCADE,
    FOREIGN KEY (tipo_comprobante_id) REFERENCES tipos_comprobante(id),
    FOREIGN KEY (tercero_id) REFERENCES terceros(id),
    FOREIGN KEY (asiento_id) REFERENCES asientos_contables(id),
    UNIQUE KEY unique_comprobante (empresa_id, tipo_comprobante_id, serie, numero),
    INDEX idx_empresa_fecha (empresa_id, fecha_emision),
    INDEX idx_tercero (tercero_id),
    INDEX idx_estado (estado),
    INDEX idx_tipo_operacion (tipo_operacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: cuentas_bancarias
-- ============================================
CREATE TABLE cuentas_bancarias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    banco VARCHAR(100) NOT NULL,
    numero_cuenta VARCHAR(50) NOT NULL,
    tipo_cuenta ENUM('AHORROS', 'CORRIENTE', 'CTS') DEFAULT 'CORRIENTE',
    moneda ENUM('PEN', 'USD') DEFAULT 'PEN',
    cuenta_contable_id INT NOT NULL COMMENT 'Cuenta del plan contable',
    saldo_inicial DECIMAL(15, 2) DEFAULT 0.00,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    FOREIGN KEY (cuenta_contable_id) REFERENCES plan_contable(id),
    INDEX idx_empresa (empresa_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: movimientos_bancarios
-- ============================================
CREATE TABLE movimientos_bancarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cuenta_bancaria_id INT NOT NULL,
    fecha DATE NOT NULL,
    tipo_movimiento ENUM('ingreso', 'egreso') NOT NULL,
    concepto VARCHAR(255) NOT NULL,
    numero_operacion VARCHAR(50),
    monto DECIMAL(15, 2) NOT NULL,
    asiento_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cuenta_bancaria_id) REFERENCES cuentas_bancarias(id) ON DELETE CASCADE,
    FOREIGN KEY (asiento_id) REFERENCES asientos_contables(id),
    INDEX idx_cuenta_fecha (cuenta_bancaria_id, fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: saldos_cuentas (Para optimizar consultas)
-- ============================================
CREATE TABLE saldos_cuentas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    periodo_id INT NOT NULL,
    cuenta_id INT NOT NULL,
    saldo_inicial_debe DECIMAL(15, 2) DEFAULT 0.00,
    saldo_inicial_haber DECIMAL(15, 2) DEFAULT 0.00,
    debe DECIMAL(15, 2) DEFAULT 0.00,
    haber DECIMAL(15, 2) DEFAULT 0.00,
    saldo_final DECIMAL(15, 2) DEFAULT 0.00,
    naturaleza_saldo ENUM('DEUDORA', 'ACREEDORA'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    FOREIGN KEY (periodo_id) REFERENCES periodos_contables(id) ON DELETE CASCADE,
    FOREIGN KEY (cuenta_id) REFERENCES plan_contable(id) ON DELETE CASCADE,
    UNIQUE KEY unique_saldo (empresa_id, periodo_id, cuenta_id),
    INDEX idx_periodo_cuenta (periodo_id, cuenta_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: tipos_libro_ple
-- ============================================
CREATE TABLE tipos_libro_ple (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estructura TEXT COMMENT 'JSON con la estructura del archivo',
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: archivos_ple_generados
-- ============================================
CREATE TABLE archivos_ple_generados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    periodo_id INT NOT NULL,
    tipo_libro_id INT NOT NULL,
    nombre_archivo VARCHAR(255) NOT NULL,
    ruta_archivo VARCHAR(500) NOT NULL,
    cantidad_registros INT DEFAULT 0,
    estado ENUM('generado', 'enviado', 'aceptado', 'rechazado') DEFAULT 'generado',
    usuario_id INT NOT NULL,
    fecha_generacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    FOREIGN KEY (periodo_id) REFERENCES periodos_contables(id) ON DELETE CASCADE,
    FOREIGN KEY (tipo_libro_id) REFERENCES tipos_libro_ple(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_empresa_periodo (empresa_id, periodo_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: logs_auditoria
-- ============================================
CREATE TABLE logs_auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    accion VARCHAR(50) NOT NULL,
    tabla VARCHAR(50),
    registro_id INT,
    descripcion TEXT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_usuario_fecha (usuario_id, created_at),
    INDEX idx_tabla_registro (tabla, registro_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Insertar datos iniciales
-- ============================================

-- Usuario administrador por defecto
INSERT INTO usuarios (username, password, nombre, apellido, email, rol) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'Sistema', 'admin@sistema.com', 'admin');
-- Contraseña: admin123

-- Tipos de comprobante según SUNAT
INSERT INTO tipos_comprobante (codigo, descripcion, abreviatura, sunat_codigo) VALUES
('00', 'Otros', 'OTR', '00'),
('01', 'Factura', 'FAC', '01'),
('03', 'Boleta de Venta', 'BOL', '03'),
('04', 'Liquidación de compra', 'LIQ', '04'),
('07', 'Nota de Crédito', 'NC', '07'),
('08', 'Nota de Débito', 'ND', '08'),
('09', 'Guía de Remisión Remitente', 'GRR', '09'),
('12', 'Ticket de Máquina Registradora', 'TMR', '12'),
('14', 'Recibo por Servicios Públicos', 'RSP', '14'),
('16', 'Voucher de Venta', 'VOU', '16'),
('31', 'Guía de Remisión Transportista', 'GRT', '31'),
('50', 'Recibo de Arrendamiento', 'RAR', '50'),
('91', 'Comprobante de No Domiciliado', 'CND', '91');

-- Tipos de libros PLE
INSERT INTO tipos_libro_ple (codigo, nombre, descripcion) VALUES
('050100', 'Libro Diario', 'Libro Diario - Formato 5.1'),
('050200', 'Libro Diario - Simplificado', 'Libro Diario de Formato Simplificado - Formato 5.2'),
('060100', 'Libro Mayor', 'Libro Mayor - Formato 6.1'),
('010100', 'Libro Caja y Bancos', 'Libro Caja y Bancos - Detalle de los Movimientos del Efectivo - Formato 1.1'),
('080100', 'Registro de Compras', 'Registro de Compras - Formato 8.1'),
('140100', 'Registro de Ventas', 'Registro de Ventas e Ingresos - Formato 14.1'),
('030100', 'Libro de Inventarios y Balances', 'Libro de Inventarios y Balances - Balance General - Formato 3.1');

-- ============================================
-- Triggers
-- ============================================

-- Trigger para actualizar totales del asiento
DELIMITER //
CREATE TRIGGER actualizar_totales_asiento AFTER INSERT ON detalle_asientos
FOR EACH ROW
BEGIN
    UPDATE asientos_contables
    SET
        total_debe = (SELECT SUM(debe) FROM detalle_asientos WHERE asiento_id = NEW.asiento_id),
        total_haber = (SELECT SUM(haber) FROM detalle_asientos WHERE asiento_id = NEW.asiento_id)
    WHERE id = NEW.asiento_id;
END//

CREATE TRIGGER actualizar_totales_asiento_update AFTER UPDATE ON detalle_asientos
FOR EACH ROW
BEGIN
    UPDATE asientos_contables
    SET
        total_debe = (SELECT SUM(debe) FROM detalle_asientos WHERE asiento_id = NEW.asiento_id),
        total_haber = (SELECT SUM(haber) FROM detalle_asientos WHERE asiento_id = NEW.asiento_id)
    WHERE id = NEW.asiento_id;
END//

CREATE TRIGGER actualizar_totales_asiento_delete AFTER DELETE ON detalle_asientos
FOR EACH ROW
BEGIN
    UPDATE asientos_contables
    SET
        total_debe = (SELECT COALESCE(SUM(debe), 0) FROM detalle_asientos WHERE asiento_id = OLD.asiento_id),
        total_haber = (SELECT COALESCE(SUM(haber), 0) FROM detalle_asientos WHERE asiento_id = OLD.asiento_id)
    WHERE id = OLD.asiento_id;
END//

DELIMITER ;

-- ============================================
-- Vistas útiles
-- ============================================

-- Vista para libro diario
CREATE VIEW vista_libro_diario AS
SELECT
    a.id AS asiento_id,
    a.numero_asiento,
    a.fecha,
    a.glosa AS glosa_asiento,
    tc.descripcion AS tipo_comprobante,
    a.serie_comprobante,
    a.numero_comprobante,
    d.numero_linea,
    pc.codigo AS codigo_cuenta,
    pc.descripcion AS descripcion_cuenta,
    d.glosa AS glosa_detalle,
    d.debe,
    d.haber,
    e.razon_social AS empresa,
    e.ruc,
    p.anio,
    p.mes
FROM asientos_contables a
INNER JOIN detalle_asientos d ON a.id = d.asiento_id
INNER JOIN plan_contable pc ON d.cuenta_id = pc.id
INNER JOIN empresas e ON a.empresa_id = e.id
INNER JOIN periodos_contables p ON a.periodo_id = p.id
LEFT JOIN tipos_comprobante tc ON a.tipo_comprobante_id = tc.id
WHERE a.estado = 'registrado'
ORDER BY a.fecha, a.numero_asiento, d.numero_linea;

-- Vista para libro mayor
CREATE VIEW vista_libro_mayor AS
SELECT
    pc.id AS cuenta_id,
    pc.codigo,
    pc.descripcion,
    a.fecha,
    a.numero_asiento,
    a.glosa,
    d.debe,
    d.haber,
    e.id AS empresa_id,
    e.razon_social AS empresa,
    p.id AS periodo_id,
    p.anio,
    p.mes
FROM plan_contable pc
LEFT JOIN detalle_asientos d ON pc.id = d.cuenta_id
LEFT JOIN asientos_contables a ON d.asiento_id = a.id AND a.estado = 'registrado'
LEFT JOIN empresas e ON pc.empresa_id = e.id
LEFT JOIN periodos_contables p ON a.periodo_id = p.id
ORDER BY pc.codigo, a.fecha, a.numero_asiento;

-- ============================================
-- Fin del script
-- ============================================
