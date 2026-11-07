#!/bin/sh
set -e

echo "==> Blood Lagbe Startup Script"

# Ensure storage directories exist with proper permissions
mkdir -p storage/logs \
         storage/framework/sessions \
         storage/framework/views \
         storage/framework/cache/data \
         bootstrap/cache

chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Dynamic port binding for Render
PORT="${PORT:-10000}"
echo "==> Configuring Nginx port: ${PORT}..."
sed -i "s/__PORT__/${PORT}/g" /etc/nginx/nginx.conf

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "==> Generating application key..."
    php artisan key:generate --force
fi

# Optimize for production
echo "==> Optimizing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "==> Running database migrations..."
php artisan migrate --force --no-interaction

# Seed database asynchronously so web server starts immediately without timeout
echo "==> Scheduling initial seed..."
(sleep 2 && php artisan db:seed --force --no-interaction 2>/dev/null || true) &

# Start Nginx & PHP-FPM immediately via Supervisor
echo "==> Starting web server on port ${PORT}..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
