#!/bin/sh
set -e

# Fix permissions for node_modules volume (created as root by Docker)
if [ -d "node_modules" ]; then
    echo "Fixing node_modules permissions..."
    chown -R nodeapp:nodeapp node_modules 2>/dev/null || true
fi

# Ensure cache directories have correct permissions
chown -R nodeapp:nodeapp /home/nodeapp/.npm /home/nodeapp/.yarn 2>/dev/null || true

# Install dependencies if package.json exists and node_modules is empty/incomplete
if [ -f "package.json" ] && [ ! -f "node_modules/.yarn-integrity" ]; then
    echo "Installing Node.js dependencies..."
    if [ -f "yarn.lock" ]; then
        su-exec nodeapp yarn install
    else
        su-exec nodeapp npm install
    fi
fi

# Start Vite dev server if AUTO_START_VITE is set
if [ "${AUTO_START_VITE:-false}" = "true" ]; then
    echo "Starting Vite dev server..."
    exec su-exec nodeapp npm run dev -- --host
else
    # Execute the passed command as nodeapp user
    exec su-exec nodeapp "$@"
fi