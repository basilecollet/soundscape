#!/bin/bash

set -e

echo "🔧 Preparing storage directories for FS Bucket mount..."

# Create storage directories if they don't exist
mkdir -p storage/app/private
mkdir -p storage/app/public

# Set proper permissions
chmod -R 775 storage/app

echo "✅ Storage directories prepared successfully!"
