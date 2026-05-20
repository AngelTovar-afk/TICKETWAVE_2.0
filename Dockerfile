# ─────────────────────────────────────────────────────────────────
# Stage 1 — Build de assets con Node
# ─────────────────────────────────────────────────────────────────
FROM node:20-alpine AS assets

WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ─────────────────────────────────────────────────────────────────
# Stage 2 — Imagen de producción
# ─────────────────────────────────────────────────────────────────
FROM php:8.3-fpm

# ─── Dependencias del sistema ─────────────────────────────────────
RUN apt-get update && apt-get install -y \
  nginx \
  supervisor \
  procps \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  libzip-dev \
  libicu-dev \
  libsodium-dev \
  zip \
  unzip \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

# ─── Extensiones PHP ──────────────────────────────────────────────
RUN docker-php-ext-install \
  pdo_mysql \
  mbstring \
  exif \
  pcntl \
  bcmath \
  gd \
  zip \
  intl \
  sodium

# ─── Composer ─────────────────────────────────────────────────────
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# ─── Código fuente ────────────────────────────────────────────────
WORKDIR /var/www

COPY . .

# Certificado SSL para Aiven
COPY docker/ssl/ca.pem /etc/ssl/aiven/ca.pem
RUN chmod 644 /etc/ssl/aiven/ca.pem

# Assets compilados desde stage 1
COPY --from=assets /app/public/build ./public/build

# ─── Dependencias PHP (sin dev) ───────────────────────────────────
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ─── Permisos ─────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www \
  && chmod -R 755 /var/www/storage \
  && chmod -R 755 /var/www/bootstrap/cache

# ─── Configuraciones ──────────────────────────────────────────────
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh
# Crear directorios necesarios
RUN mkdir -p /var/log/supervisor \
  && mkdir -p /var/log/nginx \
  && mkdir -p /run/php
# Eliminar conf default de Nginx
RUN rm -f /etc/nginx/sites-enabled/default \
  && rm -f /etc/nginx/conf.d/default.conf.original
EXPOSE 80

CMD ["/start.sh"]