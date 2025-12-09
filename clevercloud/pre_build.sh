#!/bin/bash

set -e

echo "🔧 Preparing empty storage/app for FS Bucket mount..."

# Create empty storage/app directory (FS Bucket requires empty mount point)
mkdir -p storage/app

echo "✅ Empty storage/app directory created!"
