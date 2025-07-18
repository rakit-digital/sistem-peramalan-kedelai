FROM node:20-alpine AS frontend-builder
WORKDIR /app

COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM dunglas/frankenphp:1-php8.3

RUN apt-get update && apt-get install -y --no-install-recommends \
  curl unzip git ca-certificates \
  libpq-dev libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install pdo pdo_mysql mysqli pdo_pgsql pgsql gd zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Cache Composer deps (without scripts)
COPY composer.json composer.lock ./
RUN composer install --no-interaction --optimize-autoloader --no-scripts

# Copy full Laravel project
COPY . .

# Copy frontend build
COPY --from=frontend-builder /app/public/build ./public/build

# Re-run Composer scripts (artisan now exists)
RUN composer dump-autoload --optimize && \
    composer run-script post-autoload-dump || true

# Laravel post-setup
RUN php artisan storage:link || true
RUN php artisan optimize || true

EXPOSE 80
