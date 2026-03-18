# Production PHP-FPM image for Laravel (PHP 8.3)
FROM php:8.3-fpm-alpine AS base

# Build deps for extensions (removed in same layer for smaller image)
RUN apk add --no-cache \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    linux-headers \
    $PHPIZE_DEPS

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        exif \
        gd \
        intl \
        opcache \
        pcntl \
        pdo_mysql \
        zip

# Redis extension (production preferred over predis)
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Production PHP settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Custom production php.ini
COPY docker/php/php-production.ini /usr/local/etc/php/conf.d/99-production.ini

# OPcache for production
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# PHP-FPM: run pool as app user (match volume ownership); listen on all interfaces so nginx can connect
RUN set -eux; \
    sed -i 's/^user = .*/user = app/' /usr/local/etc/php-fpm.d/www.conf; \
    sed -i 's/^group = .*/group = app/' /usr/local/etc/php-fpm.d/www.conf; \
    echo 'user = app' >> /usr/local/etc/php-fpm.d/zz-docker.conf; \
    echo 'group = app' >> /usr/local/etc/php-fpm.d/zz-docker.conf; \
    echo 'listen = 9000' >> /usr/local/etc/php-fpm.d/zz-docker.conf

# app user for PHP-FPM pool workers (master runs as root for error_log to stderr)
RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Install production composer dependencies only (no dev)
RUN apk add --no-cache unzip git \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --no-scripts --no-autoloader --prefer-dist \
    && composer clear-cache \
    && apk del unzip git

# Copy application code
COPY --chown=app:app . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --classmap-authoritative

# Ensure storage and bootstrap/cache writable (for when copied to volume)
RUN chown -R app:app storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Entrypoint: populate shared volume on first run, then exec as app user
COPY docker/app/entrypoint.sh /entrypoint.sh
RUN sed -i 's/\r$//' /entrypoint.sh && chmod +x /entrypoint.sh
# Healthcheck script (avoids CMD-SHELL quoting issues)
COPY docker/php/healthcheck.sh /usr/local/bin/php-fpm-healthcheck
RUN sed -i 's/\r$//' /usr/local/bin/php-fpm-healthcheck && chmod +x /usr/local/bin/php-fpm-healthcheck
ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 9000

CMD ["php-fpm"]
