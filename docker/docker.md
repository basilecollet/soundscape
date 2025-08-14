# Docker Development Environment

This document provides comprehensive documentation for the optimized Docker development environment of the Soundscape Laravel application.

## 📋 Table of Contents

- [Overview](#overview)
- [Prerequisites](#prerequisites)
- [Quick Start](#quick-start)
- [Architecture](#architecture)
- [Makefile Commands](#makefile-commands)
- [Docker Configuration](#docker-configuration)
- [Development Workflow](#development-workflow)
- [Debugging](#debugging)
- [Troubleshooting](#troubleshooting)
- [Performance Optimization](#performance-optimization)
- [Security](#security)

## Overview

This project uses an optimized Docker setup specifically designed for **development purposes**. The production deployment is handled separately on Clever Cloud.

### Key Features

- 🚀 **Multi-stage builds** for optimized image sizes
- 🔒 **Security-first approach** with non-root users
- ⚡ **Performance optimized** with proper volumes and caching
- 🛠️ **Developer-friendly** with Makefile shortcuts
- 🔍 **Xdebug integrated** for PHP debugging
- 🔄 **Hot-reload** for frontend development
- ✅ **Health checks** on all services

## Prerequisites

- Docker Desktop 20.10+ or Docker Engine with Docker Compose
- Make (optional but recommended for using shortcuts)
- 4GB+ RAM allocated to Docker
- 10GB+ free disk space

## Quick Start

### 🚀 Automated Setup (Recommended)

```bash
# Make the initialization script executable
chmod +x docker/init-dev.sh

# Run the automated setup
make init

# Start development with Vite
make dev
```

### 📦 Manual Setup

```bash
# 1. Copy environment file
cp .env.docker .env
# Or if .env.docker doesn't exist:
cp .env.example .env

# 2. Build containers
docker-compose build

# 3. Start containers
docker-compose up -d

# 4. Install dependencies
docker-compose exec app composer install
docker-compose exec node yarn install

# 5. Generate application key
docker-compose exec app php artisan key:generate

# 6. Run migrations and seeders
docker-compose exec app php artisan migrate --seed

# 7. Start Vite dev server
docker-compose exec node yarn dev --host
```

## Architecture

### Container Structure

```
┌─────────────────────────────────────────────────────────────┐
│                         NGINX (Alpine)                       │
│                          Port: 8000                          │
│                    (Reverse Proxy & Static)                  │
└──────────────┬──────────────────────────┬──────────────────┘
               │                          │
       ┌───────▼────────┐        ┌───────▼────────┐
       │  PHP-FPM 8.3   │        │   Node.js 20   │
       │    (Alpine)    │        │    (Alpine)    │
       │   + Composer   │        │    + Vite      │
       │   + Xdebug     │        │  Port: 5173    │
       └───────┬────────┘        └────────────────┘
               │
       ┌───────▼────────┐
       │ PostgreSQL 15  │
       │    (Alpine)    │
       │  Port: 5432*   │
       └────────────────┘
       * Only exposed in development
```

### Container Details

| Container | Image | Purpose | Ports | User |
|-----------|-------|---------|-------|------|
| `laravel-app` | PHP 8.3-FPM Alpine | Laravel application | 9000 (internal) | www (1000) |
| `laravel-node` | Node 20 Alpine | Frontend build & dev | 5173 | nodeapp (1000) |
| `laravel-nginx` | Nginx Alpine | Web server | 8000 | root |
| `laravel-db` | PostgreSQL 15 Alpine | Database | 5432 (dev only) | postgres |

### Volume Structure

```yaml
volumes:
  postgres-data     # Persistent database storage
  composer-cache    # Composer package cache
  node-modules      # Node modules (performance)
  yarn-cache        # Yarn package cache
  npm-cache         # NPM package cache
```

## Makefile Commands

The project includes a comprehensive Makefile for simplified Docker operations:

### 🎯 Essential Commands

```bash
make help          # Show all available commands
make init          # Complete initialization
make dev           # Start development (containers + Vite)
make stop          # Stop development
make status        # Show full status
```

### 🐳 Docker Management

```bash
make up            # Start containers
make down          # Stop containers
make restart       # Restart containers
make build         # Rebuild containers
make logs          # View container logs
make ps            # Show container status
```

### 🛠️ Laravel Operations

```bash
make artisan cmd="..."   # Run any artisan command
make migrate             # Run migrations
make seed                # Seed database
make fresh               # Fresh migration with seeding
make tinker              # Start Laravel Tinker
make queue               # Start queue worker
make cache-clear         # Clear all caches
```

### 🧪 Testing & Quality

```bash
make test                # Run all tests
make test-coverage       # Run tests with coverage
make pint                # Format code with Laravel Pint
make pint-test           # Check code style without fixing
```

### 📦 Dependencies

```bash
make composer cmd="..."       # Run composer commands
make composer-install         # Install PHP dependencies
make composer-update          # Update PHP dependencies
make yarn cmd="..."           # Run yarn commands
make yarn-install            # Install Node dependencies
```

### 🎨 Frontend Development

```bash
make vite                # Start Vite dev server
make vite-build          # Build for production
```

### 💾 Database Operations

```bash
make shell-db            # Access PostgreSQL shell
make db-backup           # Create database backup
make db-restore file=... # Restore from backup
```

### 🔍 Container Access

```bash
make shell               # PHP container shell
make shell-node          # Node container shell
make shell-db            # Database shell
```

## Docker Configuration

### File Structure

```
docker/
├── init-dev.sh           # Automated setup script
├── php/
│   ├── php.ini          # PHP configuration
│   ├── php-fpm.conf     # PHP-FPM pool config
│   └── entrypoint.sh    # PHP container startup
├── node/
│   └── entrypoint.sh    # Node container startup
└── nginx/
    └── conf.d/
        └── app.conf     # Nginx site configuration
```

### Environment Configuration

The project uses multiple environment files:

- `.env` - Main environment file (git-ignored)
- `.env.example` - Template for environment variables
- `.env.docker` - Pre-configured for Docker development
- `docker-compose.yml` - Main Docker configuration
- `docker-compose.override.yml` - Local development overrides

### Key Environment Variables

```bash
# Database (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

# Application
APP_ENV=local
APP_DEBUG=true

# Vite HMR
VITE_HMR_HOST=localhost
```

## Development Workflow

### Daily Development Flow

1. **Start your day:**
   ```bash
   make up          # Start containers
   make vite        # Start Vite in another terminal
   ```

2. **During development:**
   ```bash
   make artisan cmd="make:model Product -m"  # Create models
   make migrate                               # Run migrations
   make test                                  # Run tests
   make pint                                  # Format code
   ```

3. **End of day:**
   ```bash
   make down        # Stop containers
   ```

### Working with Git

```bash
# Before committing
make pint            # Format code
make test            # Run tests

# After pulling changes
make composer-install   # Update PHP dependencies
make yarn-install      # Update Node dependencies
make migrate           # Run new migrations
```

## Debugging

### PHP Debugging with Xdebug

Xdebug is pre-configured and ready to use:

1. **VSCode Configuration** (`.vscode/launch.json`):
   ```json
   {
     "version": "0.2.0",
     "configurations": [
       {
         "name": "Listen for Xdebug",
         "type": "php",
         "request": "launch",
         "port": 9003,
         "pathMappings": {
           "/var/www/html": "${workspaceFolder}"
         }
       }
     ]
   }
   ```

2. **PHPStorm Configuration:**
   - Server: localhost:8000
   - Path mapping: `/var/www/html` → project root
   - Port: 9003

3. **Enable/Disable Xdebug:**
   ```bash
   # Edit docker-compose.override.yml
   XDEBUG_MODE=develop,debug  # Enable
   XDEBUG_MODE=off           # Disable
   ```

### Viewing Logs

```bash
# All containers
make logs

# Specific container
docker-compose logs -f app
docker-compose logs -f node
docker-compose logs -f nginx
docker-compose logs -f db

# Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log
```

## Troubleshooting

### Common Issues and Solutions

#### 1. Port Already in Use

**Error:** "bind: address already in use"

**Solution:**
```bash
# Find process using the port
lsof -i :8000  # or :5173, :5432

# Kill the process or change ports in docker-compose.override.yml
```

#### 2. Permission Issues

**Error:** "Permission denied" on storage or cache

**Solution:**
```bash
docker-compose exec app chown -R www:www storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

#### 3. Database Connection Failed

**Error:** "SQLSTATE[08006] could not connect to server"

**Solution:**
```bash
# Check if database is healthy
docker-compose ps db

# View database logs
docker-compose logs db

# Restart database
docker-compose restart db

# Ensure .env has correct credentials
DB_HOST=db
DB_PORT=5432
```

#### 4. Vite Not Accessible

**Error:** Cannot access http://localhost:5173

**Solution:**
```bash
# Start Vite with host flag
make vite
# Or manually:
docker-compose exec node yarn dev --host

# Check if node container is running
docker-compose ps node
```

#### 5. Composer/Yarn Out of Memory

**Solution:**
```bash
# Increase Docker memory allocation (Docker Desktop)
# Preferences → Resources → Memory: 4GB+

# Or use swap
docker-compose exec app php -d memory_limit=-1 /usr/bin/composer install
```

#### 6. Slow Performance on Mac/Windows

**Solution:**
```bash
# Use cached volumes (already configured)
# Ensure vendor/ and node_modules/ are in volumes
# Consider using Docker Desktop's virtualization settings
```

### Reset Everything

If you need a complete fresh start:

```bash
# Stop and remove everything
make clean-docker

# Remove vendor and node_modules
rm -rf vendor node_modules

# Start fresh
make init
```

## Performance Optimization

### Build Performance

```bash
# Enable BuildKit for faster builds
export DOCKER_BUILDKIT=1
export COMPOSE_DOCKER_CLI_BUILD=1

# Use cache mounts (already configured in Dockerfiles)
```

### Runtime Performance

1. **Volumes are optimized:**
   - `vendor/` and `node_modules/` use named volumes
   - Database data persisted in volumes
   - Cache directories use volumes

2. **OPcache configured** for development:
   - Revalidation enabled
   - Memory: 128MB
   - Fast shutdown enabled

3. **Development tips:**
   - Use `make` commands instead of typing full Docker commands
   - Keep containers running during development
   - Use `docker-compose exec` instead of `run` for better performance

## Security

### Security Features

✅ **Non-root users** in containers (www, nodeapp)
✅ **No hardcoded credentials** - uses environment variables
✅ **PostgreSQL not exposed** in production
✅ **Read-only volumes** where possible
✅ **Health checks** on all services
✅ **Minimal Alpine images** for smaller attack surface
✅ **.dockerignore** prevents sensitive files from being copied

### Best Practices

1. **Never commit `.env` files** to version control
2. **Use different passwords** for production
3. **Regularly update** base images
4. **Scan images** for vulnerabilities:
   ```bash
   docker scan laravel-app
   ```

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Docker Documentation](https://docs.docker.com)
- [Livewire Documentation](https://livewire.laravel.com)
- [Vite Documentation](https://vitejs.dev)

## Support

For issues or questions:
1. Check the [Troubleshooting](#troubleshooting) section
2. Run `make status` to diagnose issues
3. Check logs with `make logs`
4. Consult the CLAUDE.md file for AI assistance guidelines

---

**Note:** This Docker setup is optimized for local development. Production deployment should be handled through Clever Cloud or your preferred hosting platform.