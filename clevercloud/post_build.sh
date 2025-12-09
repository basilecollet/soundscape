#!/bin/bash

set -e

echo "🚀 Running post-build hooks for Soundscape..."

# Run database migrations
echo "📊 Running migrations..."
php artisan migrate --force --no-interaction

# Create the necessary sub folders
echo "📁 Create the necessary sub folders..."
mkdir -p storage/app/private storage/app/public

# Create storage link if it doesn't exist
echo "🔗 Creating storage link..."
php artisan storage:link --force

# Clear and cache config for production
echo "⚡ Optimizing application..."
php artisan optimize

echo "✅ Post-build hooks completed successfully!"
