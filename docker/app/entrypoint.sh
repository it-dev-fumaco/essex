#!/bin/sh
set -e
# Production container runs code from image filesystem.
# Persist only volumes you explicitly mount (e.g., uploads in storage/app/public).
if [ -f /var/www/html/artisan ]; then
    # Named volumes (e.g. storage/logs) may be owned by root by default.
    # Ensure the PHP-FPM worker user (app) can write runtime/cache/log files.
    mkdir -p \
        /var/www/html/storage/logs \
        /var/www/html/storage/framework/cache \
        /var/www/html/storage/framework/sessions \
        /var/www/html/storage/framework/views \
        /var/www/html/bootstrap/cache

    chown -R app:app \
        /var/www/html/storage/logs \
        /var/www/html/storage/framework \
        /var/www/html/bootstrap/cache \
        2>/dev/null || true

    chmod -R ug+rwX,o-rwx \
        /var/www/html/storage/logs \
        /var/www/html/storage/framework \
        /var/www/html/bootstrap/cache \
        2>/dev/null || true

    php /var/www/html/artisan storage:link --force 2>/dev/null || true
    php /var/www/html/artisan optimize:clear 2>/dev/null || true
fi
# Run PHP-FPM as root so it can write error_log to /proc/self/fd/2; pool workers run as app per www.conf
exec "$@"
