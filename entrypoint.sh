#!/bin/bash
set -e

# Configurar el puerto dinámico de Apache según la variable PORT de Render
PORT_TO_USE=${PORT:-10000}
sed -i "s/Listen 80/Listen ${PORT_TO_USE}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT_TO_USE}>/g" /etc/apache2/sites-available/000-default.conf

# Eliminar archivo hot de Vite por si se coló
rm -f /var/www/html/public/hot

# Publicar assets de AdminLTE si no están presentes
php artisan adminlte:install --only=assets --force || true

# Crear el enlace simbólico para almacenamiento de imágenes si no existe
php artisan storage:link || true

# Esperar a que la base de datos esté lista
echo "Esperando conexión a la base de datos ($DB_HOST:$DB_PORT)..."
for i in $(seq 1 30); do
    if php -r "try { new PDO('mysql:host='.getenv('DB_HOST').';port='.(getenv('DB_PORT') ?: '3306'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (\Exception \$e) { exit(1); }"; then
        echo "Base de datos lista."
        break
    fi
    echo "Esperando a la base de datos... ($i/30)"
    sleep 2
done

# Ejecutar migraciones de Laravel para crear automáticamente todas las tablas
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force || php artisan migrate:fresh --force || true

# Ejecutar seeders esenciales (roles, sucursales, métodos de pago y usuarios)
echo "Ejecutando seeders de datos iniciales..."
php artisan db:seed --class=RoleSeeder --force || true
php artisan db:seed --class=SucursalSeeder --force || true
php artisan db:seed --class=MetodoPagoSeeder --force || true
php artisan db:seed --class=AdminUserSeeder --force || true
php artisan db:seed --class=InitialUsersSeeder --force || true

# Optimizar cachés de Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Asegurar permisos para el usuario www-data de Apache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Iniciar Apache en primer plano
exec apache2-foreground
