#!/bin/sh
set -e

echo "==> Blood Lagbe Startup Script"

# Ensure storage directories exist
mkdir -p storage/logs \
         storage/framework/sessions \
         storage/framework/views \
         storage/framework/cache/data \
         bootstrap/cache

chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "==> Generating application key..."
    php artisan key:generate --force
fi

# Run database migrations
echo "==> Running database migrations..."
php artisan migrate --force --no-interaction

# Seed database (only if DB is empty)
echo "==> Checking if seeding is needed..."
php artisan db:seed --force --no-interaction 2>/dev/null || true

# Optimize for production
echo "==> Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
