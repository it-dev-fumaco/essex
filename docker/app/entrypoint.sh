#!/bin/sh
set -e
# Production container runs code from image filesystem.
# Persist only volumes you explicitly mount (e.g., uploads in storage/app/public).
if [ -f /var/www/html/artisan ]; then
    php /var/www/html/artisan storage:link --force 2>/dev/null || true
    php /var/www/html/artisan optimize:clear 2>/dev/null || true
fi
# Run PHP-FPM as root so it can write error_log to /proc/self/fd/2; pool workers run as app per www.conf
exec "$@"
