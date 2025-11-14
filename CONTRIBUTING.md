# Guía de Contribución

¡Gracias por tu interés en contribuir al Sistema de Contabilidad Perú! Esta guía te ayudará a entender cómo puedes participar en el desarrollo.

## Cómo Contribuir

### Reportar Bugs

Si encuentras un error:

1. Verifica que el bug no haya sido reportado previamente en Issues
2. Crea un nuevo issue con:
   - Descripción clara del problema
   - Pasos para reproducir
   - Comportamiento esperado vs. actual
   - Capturas de pantalla si es relevante
   - Versión de PHP, MySQL y navegador

### Sugerir Mejoras

Para sugerir nuevas características:

1. Verifica que no exista una sugerencia similar
2. Crea un issue describiendo:
   - El problema que resuelve
   - La solución propuesta
   - Beneficios esperados
   - Posibles alternativas consideradas

### Pull Requests

#### Proceso

1. **Fork** el repositorio
2. **Clona** tu fork localmente
3. **Crea** una rama para tu feature: `git checkout -b feature/nueva-caracteristica`
4. **Realiza** tus cambios
5. **Prueba** tus cambios exhaustivamente
6. **Commit** con mensajes descriptivos
7. **Push** a tu fork: `git push origin feature/nueva-caracteristica`
8. **Crea** un Pull Request

#### Estándares de Código

**PHP:**
- Seguir PSR-12 para estilo de código
- Documentar todas las funciones y clases
- Usar type hints cuando sea posible
- Nombres de variables descriptivos en español
- Comentarios en español

```php
/**
 * Crear nuevo asiento contable
 *
 * @param array $datos Datos del asiento
 * @return int ID del asiento creado
 * @throws Exception Si el asiento no está balanceado
 */
public function crearAsiento(array $datos): int
{
    // Implementación
}
```

**JavaScript:**
- Usar ES6+ cuando sea posible
- Comentarios descriptivos
- Funciones con nombres claros

**CSS:**
- Usar clases descriptivas
- Seguir metodología BEM cuando aplique
- Comentar secciones principales

**SQL:**
- Nombres de tablas en plural y minúsculas
- Nombres de columnas descriptivos en snake_case
- Índices en columnas frecuentemente consultadas
- Comentarios en estructuras complejas

#### Commits

Formato de mensajes:

```
<tipo>: <descripción corta>

<descripción detallada opcional>

<footer opcional>
```

Tipos:
- `feat`: Nueva característica
- `fix`: Corrección de bug
- `docs`: Documentación
- `style`: Formato, punto y coma faltante, etc
- `refactor`: Refactorización de código
- `test`: Agregar tests
- `chore`: Tareas de mantenimiento

Ejemplos:

```
feat: Agregar exportación de balance general a PDF

Implementa la funcionalidad para exportar el balance general
en formato PDF usando la librería TCPDF.

Closes #123
```

```
fix: Corregir cálculo de IGV en comprobantes

El cálculo de IGV no consideraba correctamente las exoneraciones.
Se agrega validación adicional.

Fixes #456
```

### Testing

Antes de enviar un PR:

1. Probar todas las funcionalidades afectadas
2. Verificar que no hay errores de PHP
3. Validar en diferentes navegadores
4. Probar en diferentes resoluciones
5. Verificar integridad de base de datos

### Documentación

Actualizar documentación cuando:

- Agregas nueva funcionalidad
- Cambias comportamiento existente
- Modificas configuración
- Actualizas requisitos del sistema

## Estilo de Base de Datos

### Tablas

- Nombres en plural: `usuarios`, `empresas`, `asientos_contables`
- Prefijos cuando sea necesario para claridad
- Evitar abreviaciones confusas

### Columnas

- `id`: Llave primaria (INT AUTO_INCREMENT)
- `created_at`: Timestamp de creación
- `updated_at`: Timestamp de actualización
- Nombres descriptivos: `razon_social`, `numero_documento`
- Foreign keys: `empresa_id`, `usuario_id`

### Índices

- Primary key en todas las tablas
- Foreign keys indexadas
- Columnas frecuentemente consultadas
- Índices compuestos cuando sea apropiado

## Estructura del Proyecto

```
contabilidad/
├── app/
│   ├── controllers/    # Controladores MVC
│   ├── models/         # Modelos de datos
│   └── views/          # Vistas (templates)
├── config/             # Archivos de configuración
├── database/           # Scripts SQL
├── public/             # Archivos públicos
│   ├── css/           # Estilos
│   ├── js/            # Scripts
│   └── index.php      # Punto de entrada
├── src/                # Código fuente
│   ├── Core/          # Núcleo del framework
│   └── Helpers/       # Helpers y utilidades
└── storage/           # Archivos generados
```

## Prioridades del Proyecto

1. **Seguridad**: Siempre priorizar seguridad
2. **Precisión contable**: Validar cálculos exhaustivamente
3. **Cumplimiento SUNAT**: Seguir normativas vigentes
4. **Usabilidad**: Interfaz intuitiva
5. **Performance**: Optimizar consultas

## Preguntas Frecuentes

**¿Puedo trabajar en un issue ya asignado?**
Comenta en el issue antes de comenzar para evitar trabajo duplicado.

**¿Cuánto tiempo toma revisar un PR?**
Generalmente 1-3 días hábiles. PRs grandes pueden tomar más tiempo.

**¿Necesito conocimientos de contabilidad?**
Ayuda, pero no es obligatorio. La documentación cubre conceptos básicos.

**¿Puedo contribuir solo con documentación?**
¡Por supuesto! La documentación es muy importante.

## Código de Conducta

- Sé respetuoso y profesional
- Acepta críticas constructivas
- Enfócate en lo mejor para el proyecto
- Ayuda a otros contribuyentes

## Licencia

Al contribuir, aceptas que tus contribuciones se licenciarán bajo la misma licencia MIT del proyecto.

## Contacto

- Issues de GitHub para bugs y features
- Discusiones de GitHub para preguntas generales

¡Gracias por contribuir! 🎉
