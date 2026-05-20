#!/bin/bash
set -e

echo "▶ Iniciando TicketWave..."

# Crear directorios necesarios para supervisor
mkdir -p /var/log/supervisor
mkdir -p /var/run

# Eliminar conf default de Nginx que entra en conflicto
rm -f /etc/nginx/sites-enabled/default
rm -f /etc/nginx/conf.d/default.conf.bak

# Generar APP_KEY si no existe
if [ -z "$APP_KEY" ]; then
    echo "⚠ APP_KEY no definida, generando..."
    php artisan key:generate --force
fi

# Crear enlace simbólico de storage
php artisan storage:link --force 2>/dev/null || true

# Ejecutar migraciones
echo "▶ Ejecutando migraciones..."
php artisan migrate --force

# Limpiar y optimizar caché
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar seeders solo si no existen usuarios
echo "▶ Verificando datos iniciales..."
php artisan db:seed --force 2>/dev/null || true

echo "▶ Iniciando servicios..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf