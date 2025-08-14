#!/bin/sh
set -e

# Install dependencies if package.json exists and node_modules is empty
if [ -f "package.json" ] && [ ! -d "node_modules" ]; then
    echo "Installing Node.js dependencies..."
    if [ -f "yarn.lock" ]; then
        yarn install
    else
        npm install
    fi
fi

# Start Vite dev server if AUTO_START_VITE is set
if [ "${AUTO_START_VITE:-false}" = "true" ]; then
    echo "Starting Vite dev server..."
    exec npm run dev -- --host
else
    # Execute the passed command
    exec "$@"
fi