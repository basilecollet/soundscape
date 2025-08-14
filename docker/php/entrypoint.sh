#!/bin/sh
set -e

# Function to wait for database
wait_for_db() {
    echo "Waiting for database to be ready..."
    until php -r "
        try {
            new PDO('pgsql:host=${DB_HOST:-db};port=${DB_PORT:-5432};dbname=${DB_DATABASE:-laravel}', '${DB_USERNAME:-laravel}', '${DB_PASSWORD:-secret}');
            echo 'Database is ready!' . PHP_EOL;
            exit(0);
        } catch (PDOException \$e) {
            echo 'Database not ready, waiting...' . PHP_EOL;
            exit(1);
        }
    "; do
        sleep 2
    done
}

# Ensure storage directories exist with correct permissions
mkdir -p storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Fix permissions if running as root (for development flexibility)
if [ "$(id -u)" = "0" ]; then
    chown -R www:www storage bootstrap/cache
    chmod -R 775 storage bootstrap/cache
fi

# Wait for database if needed
if [ "${WAIT_FOR_DB:-true}" = "true" ]; then
    wait_for_db
fi

# Run migrations if AUTO_MIGRATE is set
if [ "${AUTO_MIGRATE:-false}" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Clear and optimize if needed
if [ "${OPTIMIZE:-false}" = "true" ]; then
    echo "Optimizing application..."
    php artisan config:clear
    php artisan view:clear
    php artisan cache:clear
fi

# Publish Livewire and Flux assets if needed
if [ "${PUBLISH_ASSETS:-false}" = "true" ]; then
    echo "Publishing assets..."
    php artisan livewire:publish --assets || true
    php artisan vendor:publish --tag=flux-assets --force || true
fi

# Execute the main command
exec "$@"