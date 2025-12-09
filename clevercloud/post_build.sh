#!/bin/bash

set -e

echo "🚀 Running post-build hooks for Soundscape..."

# Verify database connection
echo "🔍 Verifying database connection..."
if ! php artisan db:show --no-interaction > /dev/null 2>&1; then
    echo "❌ Database connection failed. Aborting deployment."
    exit 1
fi
echo "✅ Database connection verified"

# Run database migrations (NEVER use migrate:fresh in production)
echo "📊 Running migrations..."
php artisan migrate --force --no-interaction

# Create the necessary sub folders inside FS Bucket mount
echo "📁 Creating storage structure inside FS Bucket..."
mkdir -p storage/app/private
mkdir -p storage/app/public
chmod -R 775 storage/app

# Create storage link if it doesn't exist
echo "🔗 Creating storage link..."
php artisan storage:link --force

# Clear and cache config for production
echo "⚡ Optimizing application..."
php artisan optimize

echo "✅ Post-build hooks completed successfully!"
