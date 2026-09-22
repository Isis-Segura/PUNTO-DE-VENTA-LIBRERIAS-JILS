#!/usr/bin/env bash
# Exit on error
set -o errexit

# Instalar dependencias de PHP con Composer
composer install --no-dev --optimize-autoloader

# Crear el enlace simbólico de storage (sin esto, las imágenes subidas
# a storage/app/public no son accesibles desde public/storage y no se muestran)
php artisan storage:link

# Limpiar y generar cachés de Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones de la base de datos (opcional si ya tienes datos de prueba)
php artisan migrate --force
