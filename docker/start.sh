#!/bin/bash
set -e

echo "▶ Iniciando TicketWave..."

# Generar APP_KEY si no existe
if [ -z "$APP_KEY" ]; then
    echo "⚠ APP_KEY no definida, generando..."
    php artisan key:generate --force
fi

# Crear enlace simbólico de storage
php artisan storage:link --force 2>/dev/null || true

# Limpiar y optimizar caché
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones
echo "▶ Ejecutando migraciones..."
php artisan migrate --force

echo "▶ Iniciando servicios..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf