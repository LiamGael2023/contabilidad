# Sistema de Contabilidad para Perú

Sistema completo de contabilidad diseñado específicamente para empresas peruanas, cumpliendo con las normativas de SUNAT.

## Características

- **Plan Contable General Empresarial (PCGE)** - Implementado según normativa peruana
- **Sistema de Asientos Contables** - Partida doble con validación automática
- **Gestión de Comprobantes** - Facturas, boletas, notas de crédito/débito
- **Libros Contables** - Libro Diario, Mayor, Caja y Bancos
- **PLE (Programa de Libros Electrónicos)** - Generación de archivos para SUNAT
- **Reportes Financieros** - Balance General, Estado de Resultados, Flujo de Efectivo
- **Gestión de Empresas** - Multi-empresa
- **Control de Períodos Contables** - Mensual y anual

## Stack Tecnológico

- **Backend**: PHP 7.4+ con arquitectura MVC
- **Base de datos**: MySQL 8.0+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Plantillas**: Bootstrap 5

## Estructura del Proyecto

```
contabilidad/
├── app/
│   ├── controllers/       # Controladores MVC
│   ├── models/           # Modelos de datos
│   └── views/            # Vistas HTML
├── config/               # Configuración
├── database/            # Scripts SQL
├── public/              # Archivos públicos (CSS, JS, imágenes)
│   ├── css/
│   ├── js/
│   └── index.php        # Punto de entrada
├── src/                 # Clases auxiliares
│   ├── Core/           # Núcleo del framework
│   ├── Helpers/        # Funciones auxiliares
│   └── Validators/     # Validadores
└── storage/            # Archivos generados (PLE, reportes)
```

## Instalación

1. **Clonar el repositorio**
```bash
git clone <repository-url>
cd contabilidad
```

2. **Configurar base de datos**
```bash
mysql -u root -p < database/schema.sql
```

3. **Configurar conexión**
Editar `config/database.php` con tus credenciales

4. **Configurar servidor web**
Apuntar el document root a `public/`

5. **Acceder al sistema**
```
http://localhost/
```

## Credenciales por Defecto

- **Usuario**: admin
- **Contraseña**: admin123

**IMPORTANTE**: Cambiar estas credenciales en producción.

## Uso

### Crear Empresa

1. Ir a **Empresas > Nueva Empresa**
2. Ingresar RUC, razón social y datos fiscales
3. El sistema iniciará el período contable automáticamente

### Registrar Asiento Contable

1. Ir a **Asientos Contables > Nuevo Asiento**
2. Seleccionar fecha y tipo de comprobante
3. Agregar líneas de débito y crédito
4. El sistema valida automáticamente que estén balanceados

### Generar PLE

1. Ir a **PLE > Generar Archivos**
2. Seleccionar período (mes/año)
3. Seleccionar tipo de libro
4. Descargar archivo TXT para SUNAT

## Plan Contable

El sistema implementa el **Plan Contable General Empresarial (PCGE)** revisado vigente:

- **Elemento 1**: Activo Disponible y Exigible
- **Elemento 2**: Activo Realizable
- **Elemento 3**: Activo Inmovilizado
- **Elemento 4**: Pasivo
- **Elemento 5**: Patrimonio
- **Elemento 6**: Gastos por Naturaleza
- **Elemento 7**: Ingresos
- **Elemento 8**: Saldos Intermediarios de Gestión
- **Elemento 9**: Contabilidad Analítica de Explotación

## Comprobantes de Pago

Tipos soportados según SUNAT:

- **01**: Factura
- **03**: Boleta de Venta
- **07**: Nota de Crédito
- **08**: Nota de Débito
- **12**: Ticket de Máquina Registradora
- **14**: Recibo por Servicios Públicos

## Libros Contables

### Libro Diario
Registro cronológico de todas las operaciones contables.

### Libro Mayor
Movimientos por cuenta contable.

### Libro Caja y Bancos
Control de efectivo y cuentas bancarias.

## PLE - Programa de Libros Electrónicos

Generación de archivos según formato SUNAT:

- **5.1**: Libro Diario
- **5.2**: Libro Diario de Formato Simplificado
- **6.1**: Libro Mayor
- **1.1**: Libro Caja y Bancos
- **8.1**: Registro de Compras
- **14.1**: Registro de Ventas e Ingresos

## Reportes Financieros

### Balance General
Estado de situación financiera clasificado.

### Estado de Resultados
Por función y por naturaleza.

### Flujo de Efectivo
Método directo e indirecto.

### Estado de Cambios en el Patrimonio
Movimientos patrimoniales.

## Seguridad

- Autenticación de usuarios
- Control de acceso por roles
- Registro de auditoría (logs)
- Validación de datos
- Protección contra SQL Injection
- Protección contra XSS

## Respaldos

Se recomienda realizar respaldos periódicos:

```bash
mysqldump -u usuario -p contabilidad > backup_$(date +%Y%m%d).sql
```

## Contribuir

1. Fork el proyecto
2. Crear branch para feature (`git checkout -b feature/NuevaCaracteristica`)
3. Commit cambios (`git commit -m 'Agregar nueva característica'`)
4. Push al branch (`git push origin feature/NuevaCaracteristica`)
5. Crear Pull Request

## Licencia

Este proyecto es de código abierto.

## Soporte

Para reportar problemas o solicitar características, crear un issue en el repositorio.

## Normativa

Este sistema cumple con:

- Plan Contable General Empresarial (PCGE)
- Resolución de Superintendencia N° 234-2006/SUNAT (PLE)
- Normas Internacionales de Información Financiera (NIIF)
- Reglamento de Comprobantes de Pago

## Autor

Sistema desarrollado para empresas peruanas.

---
**Versión**: 1.0.0
**Fecha**: 2025
