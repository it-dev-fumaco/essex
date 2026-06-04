# Production PHP-FPM image for Laravel (PHP 8.3)
# Goals:
# - keep final image small (remove build deps)
# - speed rebuilds (cache composer + node layers)
# - ensure new image tags always reflect code changes (immutable code in image; no full-code volume in prod)

FROM php:8.3-fpm AS php-base

# Build and enable PHP extensions; remove build deps after
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        gcc \
        make \
        autoconf \
        pkg-config \
        libicu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libxml2-dev \
        libonig-dev; \
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
    pecl install opentelemetry; \
    docker-php-ext-enable redis opentelemetry; \
    rm -rf /var/lib/apt/lists/*

# Production PHP settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php/php-production.ini /usr/local/etc/php/conf.d/99-production.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
RUN printf "opentelemetry.auto_trace=1\n" > /usr/local/etc/php/conf.d/98-opentelemetry.ini

# app user for PHP-FPM pool workers
RUN groupadd -g 1000 app && useradd -u 1000 -g app -m -s /bin/sh app
RUN set -eux; \
    sed -i 's/^user = .*/user = app/' /usr/local/etc/php-fpm.d/www.conf; \
    sed -i 's/^group = .*/group = app/' /usr/local/etc/php-fpm.d/www.conf; \
    echo 'listen = 9000' >> /usr/local/etc/php-fpm.d/zz-docker.conf

WORKDIR /var/www/html

FROM php-base AS composer-deps
WORKDIR /app
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --no-scripts

# Laravel Mix v2 (webpack 3 era) depends on legacy node-sass tooling which
# fails on modern Node + Alpine (musl). Use an older Debian-based Node image
# for reproducible production asset builds.
FROM node:10-buster AS node-build
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
