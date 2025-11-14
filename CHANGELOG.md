# Changelog

Todos los cambios notables de este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/lang/es/).

## [1.0.0] - 2025-01-14

### Añadido

#### Core del Sistema
- Arquitectura MVC en PHP
- Sistema de routing personalizado
- Autoloader de clases
- Gestión de sesiones y autenticación
- Protección CSRF
- Manejo de errores personalizado

#### Gestión de Empresas
- CRUD completo de empresas
- Validación de RUC
- Gestión de datos fiscales
- Soporte para múltiples empresas

#### Plan Contable
- Implementación completa del PCGE (Plan Contable General Empresarial)
- 9 elementos del plan contable
- Estructura jerárquica de cuentas (5 niveles)
- Clasificación por naturaleza (deudora/acreedora)
- Clasificación por tipo (activo, pasivo, patrimonio, ingreso, gasto)
- Carga automática del PCGE base

#### Sistema Contable
- Registro de asientos contables con partida doble
- Validación automática de balanceo
- Numeración automática de asientos
- Soporte para múltiples monedas (PEN, USD)
- Tipo de cambio configurable
- Estados de asientos (borrador, registrado, anulado)
- Anulación de asientos con motivo y auditoría

#### Períodos Contables
- Creación de períodos mensuales
- Cierre y reapertura de períodos
- Control de edición según estado del período
- Auditoría de cierres

#### Comprobantes de Pago
- Gestión de comprobantes según SUNAT
- Tipos soportados: Facturas, Boletas, Notas de Crédito, Notas de Débito
- Cálculo automático de IGV (18%)
- Soporte para detracciones, retenciones y percepciones
- Vinculación automática con asientos contables
- Registro de ventas y compras

#### Terceros
- Gestión de clientes y proveedores
- Validación de documentos de identidad
- Soporte para RUC, DNI, CE, Pasaporte
- Clasificación (cliente, proveedor, ambos)

#### Libros Contables
- Libro Diario con formato estándar
- Libro Mayor por cuenta
- Libro Caja y Bancos
- Filtros por período
- Cálculo automático de saldos
- Visualización detallada de movimientos

#### PLE (Programa de Libros Electrónicos)
- Generación de archivos para SUNAT
- Formato 5.1: Libro Diario
- Formato 6.1: Libro Mayor
- Formato 1.1: Caja y Bancos
- Formato 8.1: Registro de Compras
- Formato 14.1: Registro de Ventas
- Nomenclatura según especificaciones SUNAT
- Separador configurable (pipe |)
- Codificación UTF-8

#### Reportes Financieros
- Balance General clasificado
- Estado de Resultados por naturaleza
- Flujo de Efectivo (método directo)
- Estado de Cambios en el Patrimonio
- Cálculos automáticos de totales
- Formato profesional para impresión

#### Cuentas Bancarias
- Gestión de cuentas bancarias
- Tipos: Ahorros, Corriente, CTS
- Registro de movimientos
- Conciliación bancaria
- Vinculación con plan contable

#### Gestión de Usuarios
- Sistema de autenticación seguro
- Hash de contraseñas con bcrypt
- Roles: Admin, Contador, Asistente, Consultor
- Control de acceso por roles
- Auditoría de acciones

#### Seguridad
- Protección contra SQL Injection
- Protección contra XSS
- Sanitización de inputs
- Tokens CSRF
- Sesiones seguras
- Auditoría de acciones

#### Base de Datos
- Esquema completo en MySQL
- 16 tablas principales
- Índices optimizados
- Triggers para cálculos automáticos
- Vistas para consultas frecuentes
- Integridad referencial

#### Interfaz de Usuario
- Diseño responsive con Bootstrap 5
- Iconos con Bootstrap Icons
- Navegación intuitiva
- Mensajes flash
- Confirmaciones de acciones
- Formularios validados
- Tablas con búsqueda

#### Helpers y Utilidades
- Helper para generación de PLE
- Helper para reportes financieros
- Funciones de formateo de moneda
- Validadores personalizados
- Utilidades de fecha

### Características Técnicas

- PHP 7.4+
- MySQL 8.0+
- Arquitectura MVC
- PDO para base de datos
- Sin dependencias externas de PHP
- Bootstrap 5 para frontend
- JavaScript vanilla (sin frameworks)
- Código documentado
- Estándares PSR

### Configuración

- Modo debug configurable
- Zona horaria: America/Lima
- Moneda: PEN (Soles)
- IGV: 18%
- Separador PLE: |
- Formato de fecha: dd/mm/YYYY

### Documentación

- README.md completo
- Guía de instalación (INSTALL.md)
- Changelog
- Comentarios en código
- Estructura de proyecto documentada

## [Unreleased]

### Por Implementar (Futuras Versiones)

- Facturación electrónica (integración con SUNAT)
- Exportación a Excel de reportes
- Gráficos y dashboards avanzados
- Módulo de tesorería
- Módulo de activos fijos
- Módulo de nómina
- API REST completa
- App móvil
- Notificaciones por email
- Respaldos automáticos desde el sistema
- Multi-idioma
- Temas personalizables
- Importación masiva de datos
- Integración con bancos
- Firma digital de documentos

---

[1.0.0]: https://github.com/usuario/contabilidad/releases/tag/v1.0.0
