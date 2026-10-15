#!/bin/bash
set -e

# Configurar el puerto dinámico de Apache según la variable PORT de Render
PORT_TO_USE=${PORT:-10000}
sed -i "s/Listen 80/Listen ${PORT_TO_USE}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT_TO_USE}>/g" /etc/apache2/sites-available/000-default.conf

# Crear el enlace simbólico para almacenamiento de imágenes si no existe
php artisan storage:link || true

# Ejecutar migraciones de Laravel para crear automáticamente todas las tablas en TiDB
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force || php artisan migrate:fresh --force || true

# Ejecutar seeders esenciales (roles, sucursales, métodos de pago y usuarios)
echo "Ejecutando seeders de datos iniciales..."
php artisan db:seed --class=RoleSeeder --force || true
php artisan db:seed --class=SucursalSeeder --force || true
php artisan db:seed --class=MetodoPagoSeeder --force || true
php artisan db:seed --class=InitialUsersSeeder --force || true

# Optimizar cachés de Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar Apache en primer plano
exec apache2-foreground
