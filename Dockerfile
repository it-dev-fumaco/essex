# Production PHP-FPM image for Laravel (PHP 8.3)
# Goals:
# - keep final image small (remove build deps)
# - speed rebuilds (cache composer + node layers)
# - ensure new image tags always reflect code changes (immutable code in image; no full-code volume in prod)

FROM php:8.3-fpm-alpine AS php-base

# Runtime libs (keep)
RUN apk add --no-cache \
    icu-libs \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype \
    libxml2 \
    oniguruma

# Build and enable PHP extensions; remove build deps after
RUN set -eux; \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libxml2-dev \
        oniguruma-dev; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        opcache \
        pcntl \
        pdo_mysql \
        zip; \
    pecl install redis; \
    docker-php-ext-enable redis; \
    apk del .build-deps

# Production PHP settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php/php-production.ini /usr/local/etc/php/conf.d/99-production.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# app user for PHP-FPM pool workers
RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app
RUN set -eux; \
    sed -i 's/^user = .*/user = app/' /usr/local/etc/php-fpm.d/www.conf; \
    sed -i 's/^group = .*/group = app/' /usr/local/etc/php-fpm.d/www.conf; \
    echo 'listen = 9000' >> /usr/local/etc/php-fpm.d/zz-docker.conf

WORKDIR /var/www/html

FROM composer:2 AS composer-deps
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress

FROM node:20-alpine AS node-build
WORKDIR /app
COPY package.json webpack.mix.js ./
COPY resources ./resources
COPY public ./public
RUN npm install --no-audit --no-fund \
    && npm run production

FROM php-base AS app

COPY --chown=app:app . .
COPY --from=composer-deps /app/vendor ./vendor
COPY --from=node-build /app/public ./public

RUN chown -R app:app storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/app/entrypoint.sh /entrypoint.sh
RUN sed -i 's/\r$//' /entrypoint.sh && chmod +x /entrypoint.sh
COPY docker/php/healthcheck.sh /usr/local/bin/php-fpm-healthcheck
RUN sed -i 's/\r$//' /usr/local/bin/php-fpm-healthcheck && chmod +x /usr/local/bin/php-fpm-healthcheck

ENTRYPOINT ["/entrypoint.sh"]
EXPOSE 9000
CMD ["php-fpm"]
