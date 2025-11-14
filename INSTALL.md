# Guía de Instalación - Sistema de Contabilidad Perú

## Requisitos del Sistema

### Software Necesario
- **PHP**: 7.4 o superior
- **MySQL**: 8.0 o superior
- **Servidor Web**: Apache 2.4+ o Nginx
- **Extensiones PHP requeridas**:
  - pdo_mysql
  - mbstring
  - json
  - session

## Instalación Paso a Paso

### 1. Clonar o Descargar el Proyecto

```bash
git clone <repository-url> contabilidad
cd contabilidad
```

### 2. Configurar Servidor Web

#### Apache

**Configuración de Virtual Host:**

```apache
<VirtualHost *:80>
    ServerName contabilidad.local
    DocumentRoot "/ruta/a/contabilidad/public"

    <Directory "/ruta/a/contabilidad/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "/var/log/apache2/contabilidad-error.log"
    CustomLog "/var/log/apache2/contabilidad-access.log" combined
</VirtualHost>
```

**Habilitar mod_rewrite:**

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Nginx

**Configuración del sitio:**

```nginx
server {
    listen 80;
    server_name contabilidad.local;
    root /ruta/a/contabilidad/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 3. Configurar Base de Datos

#### Crear Base de Datos

```bash
mysql -u root -p
```

```sql
CREATE DATABASE contabilidad CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'contabilidad_user'@'localhost' IDENTIFIED BY 'tu_contraseña_segura';
GRANT ALL PRIVILEGES ON contabilidad.* TO 'contabilidad_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Importar Esquema

```bash
mysql -u contabilidad_user -p contabilidad < database/schema.sql
```

### 4. Configurar Conexión a Base de Datos

Copiar el archivo de ejemplo y editar con tus credenciales:

```bash
cp config/database.php.example config/database.php
```

Editar `config/database.php`:

```php
return [
    'driver' => 'mysql',
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'contabilidad',
    'username' => 'contabilidad_user',
    'password' => 'tu_contraseña_segura',
    // ...
];
```

### 5. Configurar Permisos

```bash
chmod -R 755 /ruta/a/contabilidad
chmod -R 775 storage/
chmod -R 775 storage/ple
chmod -R 775 storage/reports
chmod -R 775 storage/logs
```

Si usas Apache:

```bash
sudo chown -R www-data:www-data /ruta/a/contabilidad
```

Si usas Nginx:

```bash
sudo chown -R nginx:nginx /ruta/a/contabilidad
```

### 6. Configurar archivo hosts (Desarrollo local)

Editar `/etc/hosts` (Linux/Mac) o `C:\Windows\System32\drivers\etc\hosts` (Windows):

```
127.0.0.1   contabilidad.local
```

### 7. Acceder al Sistema

Abrir en el navegador:

```
http://contabilidad.local
```

**Credenciales por defecto:**
- Usuario: `admin`
- Contraseña: `admin123`

**⚠️ IMPORTANTE: Cambiar estas credenciales inmediatamente en producción.**

## Configuración de Producción

### 1. Desactivar Modo Debug

Editar `config/app.php`:

```php
'debug' => false,
```

### 2. Configurar HTTPS

Es altamente recomendado usar HTTPS en producción. Configurar certificado SSL en Apache o Nginx.

### 3. Cambiar Contraseñas

Cambiar la contraseña del usuario admin desde el sistema o directamente en la base de datos.

### 4. Configurar Respaldos Automáticos

Crear un cron job para respaldos diarios:

```bash
crontab -e
```

Agregar:

```bash
0 2 * * * mysqldump -u contabilidad_user -p'tu_contraseña' contabilidad > /ruta/backups/contabilidad_$(date +\%Y\%m\%d).sql
```

## Solución de Problemas

### Error: "No se puede conectar a la base de datos"

- Verificar credenciales en `config/database.php`
- Asegurar que MySQL esté ejecutándose
- Verificar que el usuario tenga permisos

### Error 404 en todas las rutas

- Verificar que mod_rewrite esté habilitado (Apache)
- Verificar archivos `.htaccess`
- Verificar configuración de Nginx

### Error: "Permission denied" en storage

```bash
chmod -R 775 storage/
sudo chown -R www-data:www-data storage/
```

### La página se ve sin estilos

- Verificar que la carpeta `public` sea el DocumentRoot
- Verificar permisos en carpeta `public/css` y `public/js`

## Actualizaciones

Para actualizar el sistema:

1. Respaldar base de datos
2. Respaldar archivos
3. Descargar nueva versión
4. Ejecutar migraciones si las hay
5. Limpiar caché del navegador

## Soporte

Para reportar problemas o solicitar ayuda:

- Crear un issue en GitHub
- Consultar la documentación en README.md

## Licencia

Este sistema es de código abierto. Ver archivo LICENSE para más detalles.
