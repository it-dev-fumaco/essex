#!/bin/sh
set -e
# Populate shared volume with application if empty (first run)
if [ ! -f /var/www/html/public/index.php ]; then
    cp -a /app/. /var/www/html/
    chown -R app:app /var/www/html
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
fi
# When app is present: symlink storage, clear caches so assets load with correct URLs
if [ -f /var/www/html/artisan ]; then
    php /var/www/html/artisan storage:link --force 2>/dev/null || true
    php /var/www/html/artisan config:clear 2>/dev/null || true
    php /var/www/html/artisan view:clear 2>/dev/null || true
fi
# Run PHP-FPM as root so it can write error_log to /proc/self/fd/2; pool workers run as app per www.conf
exec "$@"
