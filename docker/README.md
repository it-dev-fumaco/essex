# Docker (Essex app + nginx + Redis)

Essex ships **PHP-FPM** (`essex-app`), **nginx** (`essex-nginx`), and **Redis** (`essex-redis`) in this repo’s Compose files. Nginx serves `public/` and forwards PHP to `app:9000`; the app uses Redis on `app_network` for cache, queue, and session.

## Requirements

- Docker Engine 20.10+
- Docker Compose v2+

## Quick start

1. Copy environment and set values (`APP_KEY`, `DB_*`, etc.):

   ```bash
   cp .env.example .env
   ```

2. **Hosts file** (for `http://essex.local`): add  
   `127.0.0.1 essex.local`  
   (spelling: **essex**, not `esse.local`.)

3. Build and start:

   ```bash
   docker compose up -d --build
   ```

4. Open **http://essex.local:8080** (default maps host **8080** → nginx **80** in the container). Set **`APP_URL`** and **`ASSET_URL`** in `.env` to that same base URL. To use another host port, set **`ESSEX_HTTP_PORT`** and matching URLs, then `docker compose up -d` again.

5. Laravel setup in the app container:

   ```bash
   docker compose exec app php artisan migrate --force
   docker compose exec app php artisan config:cache
   docker compose exec app php artisan route:cache
   docker compose exec app php artisan view:cache
   ```

## Services

| Service        | Role |
|----------------|------|
| **essex-app**  | PHP-FPM `:9000`, Laravel |
| **essex-nginx**| HTTP: host `${ESSEX_HTTP_PORT:-8080}` → container `:80` |
| **essex-redis**| Redis `:6379` (AOF volume `essex_redis`) |

## Production-style stack

```bash
docker compose -f docker-compose.prod.yml up -d
```

Uses images `essex-app:v4` and `essex-nginx:v4` by default (see `docker-compose.prod.yml`). Published host port defaults to **8080** (`ESSEX_HTTP_PORT`).

## Redis

Compose sets **`REDIS_HOST=redis`** for `essex-app` (the `redis` service). Use `CACHE_DRIVER=redis`, `QUEUE_CONNECTION=redis`, and `SESSION_DRIVER=redis` in `.env` (defaults in compose already target Redis).

## Health checks

- **app**: PHP-FPM (`php-fpm-healthcheck`)
- **redis**: `redis-cli ping`

## Volumes

- `storage_public`, `storage_logs`, `essex_redis`

## Optional: queue worker

```bash
docker compose exec app php artisan queue:work redis --tries=3
```
