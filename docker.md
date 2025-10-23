# 🐳 Docker Infrastructure Documentation

Complete guide to the Soundscape Audio Docker development environment.

## 📋 Table of Contents

- [Overview](#-overview)
- [Architecture](#-architecture)
- [Docker Images](#-docker-images)
  - [PHP Application (Dockerfile)](#php-application-dockerfile)
  - [Node.js Frontend (Dockerfile.node)](#nodejs-frontend-dockerfilenode)
- [Docker Compose](#-docker-compose)
  - [Main Configuration (docker-compose.yml)](#main-configuration-docker-composeyml)
  - [Local Overrides (docker-compose.override.yml)](#local-overrides-docker-composeoverrideyml)
- [Services](#-services)
- [Makefile Commands](#-makefile-commands)
- [Volumes & Persistence](#-volumes--persistence)
- [Networks](#-networks)
- [Health Checks](#-health-checks)
- [Environment Variables](#-environment-variables)
- [Common Workflows](#-common-workflows)
- [Troubleshooting](#-troubleshooting)

---

## 🎯 Overview

The Soundscape Audio project uses a **multi-container Docker architecture** optimized for Laravel development. The setup includes:

- **PHP 8.4** (FPM) with Xdebug for application logic
- **Node.js 20** for frontend asset compilation (Vite)
- **Nginx** as reverse proxy
- **PostgreSQL 15** as database
- **Automated development workflow** via Makefile

### Key Features

✅ **Multi-stage builds** for optimized images
✅ **Health checks** on all critical services
✅ **Volume caching** for Composer and NPM
✅ **Non-root users** for security
✅ **Hot reload** with Vite HMR
✅ **Xdebug support** for debugging

---

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                         Host Machine                            │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                    Docker Engine                          │  │
│  │                                                           │  │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │  │
│  │  │  Nginx   │  │   PHP    │  │  Node.js │  │PostgreSQL│   │  │
│  │  │  :8000   │─▶│  :9000   │  │  :5173   │  │  :5432   │   │  │
│  │  └────┬─────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘   │  │
│  │       │             │             │             │         │  │
│  │       └─────────────┴─────────────┴─────────────┘         │  │
│  │                  laravel-network (bridge)                 │  │
│  │                                                           │  │
│  │  Volumes:                                                 │  │
│  │  • postgres-data    (database persistence)                │  │
│  │  • composer-cache   (PHP dependencies cache)              │  │
│  │  • node-modules     (frontend dependencies)               │  │
│  │  • npm-cache        (NPM cache)                           │  │
│  │  • yarn-cache       (Yarn cache)                          │  │
│  └───────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

### Request Flow

1. Browser → `http://localhost:8000`
2. Nginx receives request
3. Static files served directly by Nginx
4. PHP requests forwarded to PHP-FPM (app:9000)
5. Vite dev server handles HMR on port 5173

---

## 🖼️ Docker Images

### PHP Application (Dockerfile)

**Location:** `Dockerfile`
**Base Image:** `php:8.4-fpm-alpine`
**Build Strategy:** Multi-stage build (3 stages)

#### Stage 1: Base PHP Environment

```dockerfile
FROM php:8.4-fpm-alpine AS base
```

**Purpose:** Create a minimal PHP runtime with essential extensions.

**Installed System Packages:**
- `postgresql-dev` - PostgreSQL client libraries
- `libpng-dev`, `libjpeg-turbo-dev`, `freetype-dev` - Image processing (GD)
- `libzip-dev` - ZIP file manipulation
- `zip`, `unzip` - Archive handling
- `git` - Version control
- `curl` - HTTP client
- `bash` - Shell (Alpine uses ash by default)
- `supervisor` - Process manager

**Installed PHP Extensions:**
- `pdo`, `pdo_pgsql` - Database connectivity
- `zip` - Archive handling
- `gd` - Image manipulation (with FreeType & JPEG support)
- `opcache` - PHP opcode cache for performance
- `bcmath` - Arbitrary precision math
- `pcntl` - Process control
- `exif` - Image metadata reading

**Why these extensions?**
- `pdo_pgsql`: PostgreSQL database connection
- `gd`: Required by Spatie Media Library for image resizing
- `opcache`: ~30% performance improvement in production
- `bcmath`: Financial calculations, e-commerce math
- `exif`: Image orientation handling for uploads

#### Stage 2: Development Dependencies

```dockerfile
FROM base AS dev-deps
```

**Purpose:** Add development-only tools (Composer, Xdebug).

**Added Tools:**
- **Composer** (latest) - PHP dependency manager
- **Xdebug 3.4.6** - Debugging and profiling

**Xdebug Configuration:**
```ini
xdebug.mode=debug
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
xdebug.start_with_request=yes
```

**Why Xdebug?**
- Step debugging in PHPStorm/VS Code
- Code coverage analysis
- Performance profiling

#### Stage 3: Final Development Image

```dockerfile
FROM dev-deps AS development
```

**Purpose:** Production-ready development image.

**Key Features:**
- **Non-root user** (`www:www`, UID/GID 1001)
- **Custom PHP config** (`docker/php/php.ini`)
- **Custom FPM config** (`docker/php/php-fpm.conf`)
- **Automatic setup** via entrypoint script
- **Health checks** (PHP-FPM status)

**Entrypoint Script (`docker/php/entrypoint.sh`):**
1. Creates storage directories
2. Fixes permissions
3. Waits for database connection
4. Runs migrations (if `AUTO_MIGRATE=true`)
5. Clears caches (if `OPTIMIZE=true`)
6. Publishes Livewire/Flux assets (if `PUBLISH_ASSETS=true`)

**Security:**
- Runs as non-root user (`USER www`)
- Minimal Alpine base (small attack surface)
- No unnecessary packages

---

### Node.js Frontend (Dockerfile.node)

**Location:** `Dockerfile.node`
**Base Image:** `node:20-alpine`
**Build Strategy:** Multi-stage build (2 stages)

#### Stage 1: Base Node Environment

```dockerfile
FROM node:20-alpine AS base
```

**Purpose:** Create Node.js runtime for frontend tooling.

**Installed System Packages:**
- `bash` - Better shell scripting
- `git` - Required by some NPM packages
- `python3`, `make`, `g++` - Native module compilation (node-gyp)

**Why Python/Make/G++?**
Some NPM packages have native dependencies that need compilation (e.g., sharp, node-sass).

#### Stage 2: Development Image

```dockerfile
FROM base AS development
```

**Key Features:**
- **Non-root user** (`nodeapp:nodeapp`, UID/GID 1001)
- **Cache directories** for npm/yarn (volume-mounted)
- **Polling enabled** for file watching in Docker
- **Health check** on Vite dev server (port 5173)

**Environment Variables:**
```env
NODE_ENV=development
CHOKIDAR_USEPOLLING=true    # Fix file watching in Docker
WATCHPACK_POLLING=true       # Webpack/Vite polling
```

**Why polling?**
Docker volume mounts don't always trigger file system events correctly. Polling ensures hot reload works reliably.

**Entrypoint Script (`docker/node/entrypoint.sh`):**
1. Installs dependencies if `node_modules` is missing
2. Starts Vite automatically (if `AUTO_START_VITE=true`)

---

## 📦 Docker Compose

### Main Configuration (docker-compose.yml)

**Location:** `docker-compose.yml`
**Purpose:** Base infrastructure definition for all environments.

#### Services Overview

| Service | Image | Ports | Purpose |
|---------|-------|-------|---------|
| `app` | Custom (Dockerfile) | - | PHP application (FPM) |
| `node` | Custom (Dockerfile.node) | 5173 | Frontend build tools (Vite) |
| `nginx` | nginx:alpine | 8000 | Web server / reverse proxy |
| `db` | postgres:15-alpine | - | Database (internal) |

#### Service: app (PHP Application)

```yaml
app:
  build:
    context: .
    dockerfile: Dockerfile
    target: development
```

**Key Configuration:**
- **Container name:** `laravel-app`
- **Restart policy:** `unless-stopped`
- **Working directory:** `/var/www/html`
- **Depends on:** `db` (with health check)

**Environment Variables:**
```yaml
environment:
  - DB_HOST=${DB_HOST:-db}
  - DB_PORT=${DB_PORT:-5432}
  - DB_DATABASE=${DB_DATABASE:-laravel}
  - DB_USERNAME=${DB_USERNAME:-laravel}
  - DB_PASSWORD=${DB_PASSWORD:-secret}
  - WAIT_FOR_DB=true
  - PUBLISH_ASSETS=true
```

**Volumes:**
```yaml
volumes:
  - ./:/var/www/html              # Source code (bind mount)
  - composer-cache:/home/www/.composer/cache  # Composer cache
```

**Why bind mount?**
Changes to code are instantly reflected in the container (no rebuild needed).

**Health Check:**
```yaml
healthcheck:
  test: ["CMD", "php-fpm", "-t"]
  interval: 30s
  timeout: 3s
  retries: 3
```

#### Service: node (Node.js Frontend)

```yaml
node:
  build:
    context: .
    dockerfile: Dockerfile.node
    target: development
```

**Key Configuration:**
- **Container name:** `laravel-node`
- **Exposed port:** `5173` (Vite HMR)

**Volumes:**
```yaml
volumes:
  - ./:/var/www/html              # Source code
  - node-modules:/var/www/html/node_modules  # Named volume (performance)
  - yarn-cache:/home/nodeapp/.yarn          # Yarn cache
  - npm-cache:/home/nodeapp/.npm            # NPM cache
```

**Why named volume for node_modules?**
Bind-mounting `node_modules` is slow on macOS/Windows. Using a named volume dramatically improves performance.

**Health Check:**
```yaml
healthcheck:
  test: ["CMD", "node", "-e", "require('http').get('http://localhost:5173', ...)"]
  interval: 30s
  timeout: 3s
  start_period: 40s
  retries: 3
```

#### Service: nginx (Web Server)

```yaml
nginx:
  image: nginx:alpine
  ports:
    - "8000:80"
```

**Key Configuration:**
- **Container name:** `laravel-nginx`
- **Configuration:** `docker/nginx/conf.d/app.conf`

**Volumes:**
```yaml
volumes:
  - ./:/var/www/html:ro                    # Read-only source code
  - ./docker/nginx/conf.d:/etc/nginx/conf.d:ro  # Nginx config
```

**Nginx Configuration Highlights:**
- **FastCGI proxy** to `app:9000` (PHP-FPM)
- **Static file caching** (30 days for images/CSS/JS)
- **Flux UI asset handling**
- **Hidden files protection** (`.env`, `.git`)

#### Service: db (PostgreSQL)

```yaml
db:
  image: postgres:15-alpine
```

**Key Configuration:**
- **Container name:** `laravel-db`
- **Data persistence:** `postgres-data` named volume

**Environment Variables:**
```yaml
environment:
  - POSTGRES_DB=${DB_DATABASE:-laravel}
  - POSTGRES_USER=${DB_USERNAME:-laravel}
  - POSTGRES_PASSWORD=${DB_PASSWORD:-secret}
  - PGDATA=/var/lib/postgresql/data/pgdata
```

**Health Check:**
```yaml
healthcheck:
  test: ["CMD-SHELL", "pg_isready -U laravel -d laravel"]
  interval: 10s
  timeout: 5s
  retries: 5
```

---

### Local Overrides (docker-compose.override.yml)

**Location:** `docker-compose.override.yml`
**Purpose:** Local development settings (automatically merged by Docker Compose).

**Key Overrides:**

#### App Service
```yaml
app:
  environment:
    - APP_ENV=local
    - APP_DEBUG=true
    - XDEBUG_MODE=develop,debug
    - AUTO_MIGRATE=false
    - OPTIMIZE=false
```

**Debugging:**
```yaml
# Uncomment to enable Xdebug remote debugging
# ports:
#   - "9003:9003"
```

#### DB Service
```yaml
db:
  ports:
    - "5432:5432"  # Expose for local database tools
```

**When to use:**
- Connect TablePlus, DBeaver, pgAdmin to `localhost:5432`

#### Optional: Mailhog (commented out)
```yaml
# mailhog:
#   image: mailhog/mailhog:latest
#   ports:
#     - "1025:1025"  # SMTP
#     - "8025:8025"  # Web UI
```

**Use case:** Test email sending without external SMTP.

---

## 🔧 Services

### Service Details

#### PHP Application (app)
- **Image:** Custom (PHP 8.4 FPM Alpine)
- **Purpose:** Run Laravel application
- **Port:** 9000 (internal)
- **User:** `www` (UID 1001)
- **Extensions:** PDO, PostgreSQL, GD, OPcache, Xdebug
- **Entrypoint:** Automatic database wait, migrations, asset publishing

#### Node.js (node)
- **Image:** Custom (Node 20 Alpine)
- **Purpose:** Frontend asset compilation (Vite)
- **Port:** 5173 (exposed)
- **User:** `nodeapp` (UID 1001)
- **Tools:** Yarn, NPM, Vite
- **Features:** Hot Module Replacement (HMR)

#### Nginx (nginx)
- **Image:** nginx:alpine (official)
- **Purpose:** Web server & reverse proxy
- **Port:** 8000 (exposed) → 80 (internal)
- **Configuration:** Custom Laravel-optimized config
- **Features:** Static file caching, FastCGI proxy

#### PostgreSQL (db)
- **Image:** postgres:15-alpine (official)
- **Purpose:** Relational database
- **Port:** 5432 (exposed in override)
- **Data:** Persisted in `postgres-data` volume
- **Features:** Health checks, automatic initialization

---

## 🛠️ Makefile Commands

The project includes **40+ automated commands** via Makefile. All commands are prefixed with `make`.

### Quick Reference

```bash
make help           # Show all available commands with descriptions
```

### Docker Commands

| Command | Description | Usage |
|---------|-------------|-------|
| `make init` | Complete project setup | `make init` |
| `make up` | Start all containers | `make up` |
| `make down` | Stop all containers | `make down` |
| `make restart` | Restart containers | `make restart` |
| `make build` | Rebuild containers (no cache) | `make build` |
| `make logs` | View container logs (follow) | `make logs` |
| `make ps` | Show container status | `make ps` |

**Examples:**

```bash
# Start development environment
make up

# View real-time logs
make logs

# Restart after config changes
make restart
```

### Shell Access Commands

| Command | Description | Usage |
|---------|-------------|-------|
| `make shell` | Access PHP container bash | `make shell` |
| `make shell-node` | Access Node container shell | `make shell-node` |
| `make shell-db` | Access PostgreSQL shell | `make shell-db` |

**Examples:**

```bash
# Access PHP container
make shell
> php artisan about
> exit

# Access database
make shell-db
laravel=# \dt
laravel=# \q
```

### Laravel Commands

| Command | Description | Usage |
|---------|-------------|-------|
| `make artisan cmd="..."` | Run any Artisan command | `make artisan cmd="make:model Product -m"` |
| `make migrate` | Run migrations | `make migrate` |
| `make rollback` | Rollback last migration | `make rollback` |
| `make seed` | Seed database | `make seed` |
| `make fresh` | Fresh migration + seed | `make fresh` |
| `make tinker` | Start Laravel Tinker | `make tinker` |
| `make queue` | Start queue worker | `make queue` |
| `make cache-clear` | Clear all caches | `make cache-clear` |

**Examples:**

```bash
# Create a new model with migration
make artisan cmd="make:model Product -mf"

# Run migrations
make migrate

# Start fresh database
make fresh

# Clear all caches
make cache-clear
```

### Testing Commands

| Command | Description | Usage |
|---------|-------------|-------|
| `make test` | Run all tests | `make test` |
| `make test-coverage` | Run tests with coverage | `make test-coverage` |
| `make test-unit` | Run unit tests only | `make test-unit` |
| `make test-feature` | Run feature tests only | `make test-feature` |
| `make test-filter filter="..."` | Run filtered tests | `make test-filter filter="contact"` |
| `make test-file file="..."` | Run specific test file | `make test-file file=ContactTest` |
| `make tdd` | Start TDD watch mode | `make tdd` |
| `make test-watch` | Run tests in watch mode | `make test-watch` |
| `make test-parallel` | Run tests in parallel | `make test-parallel` |
| `make test-failed` | Re-run only failed tests | `make test-failed` |

**Examples:**

```bash
# Run all tests
make test

# Run tests with HTML coverage report
make test-coverage
# Open: storage/test-results/coverage/index.html

# Run specific test
make test-filter filter="contact"

# TDD mode (auto-runs on file changes)
make tdd
```

### Code Quality Commands

| Command | Description | Usage |
|---------|-------------|-------|
| `make pint` | Format code with Pint | `make pint` |
| `make pint-test` | Check formatting (no fix) | `make pint-test` |
| `make phpstan` | Run PHPStan analysis | `make phpstan` |
| `make phpstan-baseline` | Generate PHPStan baseline | `make phpstan-baseline` |
| `make quality` | Run Pint + PHPStan | `make quality` |
| `make quality-check` | Check quality (no fix) | `make quality-check` |

**Examples:**

```bash
# Format all code
make pint

# Check formatting without fixing
make pint-test

# Run static analysis
make phpstan

# Run all quality checks
make quality
```

### Composer Commands

| Command | Description | Usage |
|---------|-------------|-------|
| `make composer cmd="..."` | Run Composer command | `make composer cmd="require spatie/laravel-data"` |
| `make composer-install` | Install PHP dependencies | `make composer-install` |
| `make composer-update` | Update PHP dependencies | `make composer-update` |
| `make composer-dump` | Dump autoload | `make composer-dump` |

**Examples:**

```bash
# Install dependencies
make composer-install

# Add new package
make composer cmd="require laravel/horizon"

# Update dependencies
make composer-update
```

### Node Commands

| Command | Description | Usage |
|---------|-------------|-------|
| `make npm cmd="..."` | Run NPM command | `make npm cmd="install package"` |
| `make yarn cmd="..."` | Run Yarn command | `make yarn cmd="add package"` |
| `make yarn-install` | Install Node dependencies | `make yarn-install` |
| `make vite` | Start Vite dev server | `make vite` |
| `make vite-build` | Build for production | `make vite-build` |

**Examples:**

```bash
# Install dependencies
make yarn-install

# Start Vite (hot reload)
make vite

# Build production assets
make vite-build

# Add package
make yarn cmd="add alpinejs"
```

### Database Commands

| Command | Description | Usage |
|---------|-------------|-------|
| `make db-backup` | Backup database to SQL | `make db-backup` |
| `make db-restore file="..."` | Restore from backup | `make db-restore file=backups/backup.sql` |

**Examples:**

```bash
# Backup database
make db-backup
# Creates: backups/backup_YYYYMMDD_HHMMSS.sql

# Restore database
make db-restore file=backups/backup_20250123_150000.sql
```

### Cleanup Commands

| Command | Description | Usage |
|---------|-------------|-------|
| `make clean` | Clean everything (containers, volumes, caches) | `make clean` |
| `make clean-docker` | Remove all Docker artifacts (including images) | `make clean-docker` |

**Examples:**

```bash
# Clean up everything (keeps images)
make clean

# Nuclear option (removes images too)
make clean-docker
```

### Development Helpers

| Command | Description | Usage |
|---------|-------------|-------|
| `make dev` | Start dev environment + Vite | `make dev` |
| `make stop` | Stop dev environment | `make stop` |
| `make status` | Show full status (containers + Laravel) | `make status` |
| `make install` | Full installation (build + deps + migrate + seed) | `make install` |

**Examples:**

```bash
# Start everything for development
make dev

# Check status
make status

# Full installation from scratch
make install
```

---

## 💾 Volumes & Persistence

### Named Volumes

| Volume | Purpose | Lifecycle |
|--------|---------|-----------|
| `postgres-data` | Database data | Persisted across container restarts |
| `composer-cache` | Composer packages cache | Improves `composer install` speed |
| `node-modules` | NPM packages | Performance (avoid bind mount slowness) |
| `npm-cache` | NPM global cache | Improves `npm install` speed |
| `yarn-cache` | Yarn global cache | Improves `yarn install` speed |

### Data Persistence Strategy

**What is preserved:**
- ✅ Database data (`postgres-data`)
- ✅ Uploaded files (`./storage/app/public`)
- ✅ Application code (`./` bind mount)
- ✅ Dependency caches (composer, npm, yarn)

**What is ephemeral:**
- ❌ Container logs (use `make logs` to view)
- ❌ Running processes (restart with `make up`)
- ❌ Temporary files (`/tmp`)

**Backup Strategy:**

```bash
# Backup database
make db-backup

# Backup uploaded files
tar -czf storage-backup.tar.gz storage/app/public

# Export volumes (advanced)
docker run --rm -v soundscape_postgres-data:/data -v $(pwd)/backups:/backup \
  alpine tar czf /backup/postgres-data-backup.tar.gz -C /data .
```

---

## 🌐 Networks

### laravel-network (Bridge Network)

All services communicate via a private bridge network named `laravel-network`.

**Inter-service Communication:**

| From | To | Address |
|------|-----|---------|
| Nginx | PHP-FPM | `app:9000` |
| PHP | PostgreSQL | `db:5432` |
| Browser | Vite HMR | `localhost:5173` |
| Browser | Application | `localhost:8000` |

**DNS Resolution:**

Docker provides automatic DNS resolution. Services can reach each other by service name:

```bash
# From PHP container
ping db          # Resolves to PostgreSQL container
ping nginx       # Resolves to Nginx container

# From PostgreSQL container
ping app         # Resolves to PHP container
```

**Port Mapping:**

```
Host            Container
----------------------------
:8000     ─→    nginx:80
:5173     ─→    node:5173
:5432     ─→    db:5432 (override only)
```

---

## ❤️ Health Checks

All services have health checks for reliability.

### PHP Application (app)

```yaml
healthcheck:
  test: ["CMD", "php-fpm", "-t"]
  interval: 30s
  timeout: 3s
  retries: 3
```

**Checks:** PHP-FPM configuration validity
**Healthy when:** `php-fpm -t` returns exit code 0

### Node.js (node)

```yaml
healthcheck:
  test: ["CMD", "node", "-e", "require('http').get(...)"]
  interval: 30s
  timeout: 3s
  start_period: 40s
  retries: 3
```

**Checks:** Vite dev server responds on port 5173
**Healthy when:** HTTP 200 from `localhost:5173`
**Start period:** 40s (allows Vite to fully start)

### Nginx (nginx)

```yaml
healthcheck:
  test: ["CMD", "wget", "--quiet", "--tries=1", "--spider", "http://localhost/"]
  interval: 30s
  timeout: 10s
  retries: 3
```

**Checks:** Nginx responds to HTTP requests
**Healthy when:** HTTP response from `localhost:80`

### PostgreSQL (db)

```yaml
healthcheck:
  test: ["CMD-SHELL", "pg_isready -U laravel -d laravel"]
  interval: 10s
  timeout: 5s
  retries: 5
```

**Checks:** PostgreSQL accepts connections
**Healthy when:** `pg_isready` returns exit code 0

**View Health Status:**

```bash
# Check all containers
make ps

# Or use Docker Compose
docker compose ps

# Output shows health status:
# STATUS
# Up 2 minutes (healthy)
```

---

## 🔐 Environment Variables

### Application Configuration

**Set in:** `.env` file (root directory)

| Variable | Default | Description |
|----------|---------|-------------|
| `DB_HOST` | `db` | PostgreSQL host |
| `DB_PORT` | `5432` | PostgreSQL port |
| `DB_DATABASE` | `laravel` | Database name |
| `DB_USERNAME` | `laravel` | Database user |
| `DB_PASSWORD` | `secret` | Database password |

### Docker Compose Variables

**Set in:** `docker-compose.override.yml` or `.env`

| Variable | Default | Description |
|----------|---------|-------------|
| `WAIT_FOR_DB` | `true` | Wait for database before starting |
| `AUTO_MIGRATE` | `false` | Run migrations on start |
| `OPTIMIZE` | `false` | Clear caches on start |
| `PUBLISH_ASSETS` | `true` | Publish Livewire/Flux assets |
| `AUTO_START_VITE` | `false` | Auto-start Vite dev server |
| `XDEBUG_MODE` | `develop,debug` | Xdebug mode |

**Example Custom Configuration:**

```env
# .env
DB_DATABASE=soundscape_prod
DB_USERNAME=soundscape_user
DB_PASSWORD=super_secure_password_123

AUTO_MIGRATE=true
OPTIMIZE=true
```

---

## 🚀 Common Workflows

### 1. First Time Setup

```bash
# Clone repository
git clone <repository-url>
cd soundscape

# Run automated setup
make init

# Wait for containers to be healthy
make ps

# Access application
open http://localhost:8000
```

**What `make init` does:**
1. Creates `.env` from `.env.docker`
2. Builds Docker images
3. Starts containers
4. Installs Composer dependencies
5. Installs Node dependencies
6. Generates application key
7. Runs migrations and seeders
8. Links storage

### 2. Daily Development Workflow

```bash
# Morning - Start environment
make up

# Start Vite for hot reload (separate terminal)
make vite

# During development
make test                     # Run tests
make pint                     # Format code
make migrate                  # Apply migrations

# Create new feature
make artisan cmd="make:model Product -mf"
make test-create name=Feature/ProductTest

# Evening - Stop environment
make down
```

### 3. Running Tests

```bash
# Run all tests
make test

# Run specific test suite
make test-unit                          # Unit tests only
make test-feature                       # Feature tests only

# Run filtered tests
make test-filter filter="contact"       # Tests matching "contact"
make test-file file=ContactTest         # Specific file

# Generate coverage
make test-coverage
open storage/test-results/coverage/index.html
```

### 4. Database Operations

```bash
# Apply migrations
make migrate

# Rollback last migration
make rollback

# Fresh database
make fresh

# Seed data
make seed

# Backup before dangerous operation
make db-backup

# Access database
make shell-db
```

### 5. Code Quality

```bash
# Format code
make pint

# Check code quality
make quality-check

# Fix issues
make quality
```

### 6. Dependency Management

```bash
# Add PHP package
make composer cmd="require laravel/horizon"

# Add Node package
make yarn cmd="add alpinejs"

# Update dependencies
make composer-update
make yarn cmd="upgrade"
```

### 7. Debugging

**Enable Xdebug:**

1. Uncomment in `docker-compose.override.yml`:
```yaml
app:
  ports:
    - "9003:9003"
```

2. Restart containers:
```bash
make restart
```

3. Configure IDE:
   - **PHPStorm:** Settings → PHP → Servers → Add server `localhost:8000` mapping `/var/www/html`
   - **VS Code:** Install PHP Debug extension, use config in `.vscode/launch.json`

4. Set breakpoint and access `http://localhost:8000`

### 8. Asset Compilation

```bash
# Development (watch mode with HMR)
make vite

# Production build
make vite-build

# Check built assets
ls -lh public/build/
```

### 9. Clean Slate

```bash
# Clean everything but keep database
make down
docker volume rm soundscape_composer-cache soundscape_node-modules

# Nuclear option (removes database too)
make clean-docker
make init
```

---

## 🔍 Troubleshooting

### Common Issues & Solutions

#### 1. Port Already in Use

**Error:**
```
Error starting userland proxy: listen tcp4 0.0.0.0:8000: bind: address already in use
```

**Solution:**

```bash
# Find process using port
lsof -i :8000

# Kill process
kill -9 <PID>

# Or change port in docker-compose.override.yml
services:
  nginx:
    ports:
      - "8080:80"
```

#### 2. Permission Errors

**Error:**
```
failed to open stream: Permission denied
```

**Solution:**

```bash
# Fix permissions (from host)
sudo chown -R $USER:$USER storage bootstrap/cache

# Or from container
make shell
chown -R www:www storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

#### 3. Database Connection Failed

**Error:**
```
SQLSTATE[08006] Connection refused
```

**Solution:**

```bash
# Check database status
make ps
# db should show (healthy)

# View database logs
docker compose logs db

# Restart database
docker compose restart db

# Verify connection
make shell
php artisan tinker
> DB::connection()->getPdo();
```

#### 4. Vite Not Hot Reloading

**Error:** Changes not reflected in browser

**Solution:**

```bash
# Check Vite is running
make ps
# node should be Up

# Restart Vite
docker compose restart node
make vite

# Check browser console for connection errors
# Should see: [vite] connected

# Verify polling is enabled
docker compose exec node sh
> echo $CHOKIDAR_USEPOLLING
# Should output: true
```

#### 5. Composer Install Fails

**Error:**
```
Your requirements could not be resolved
```

**Solution:**

```bash
# Clear Composer cache
docker volume rm soundscape_composer-cache

# Update Composer
make shell
composer self-update

# Try install again
make composer-install

# If using auth (private repos)
make shell
composer config http-basic.repo.packagist.com username token
```

#### 6. Node Modules Issues

**Error:**
```
Cannot find module 'vite'
```

**Solution:**

```bash
# Remove node_modules volume
docker compose down
docker volume rm soundscape_node-modules

# Reinstall
make up
make yarn-install
```

#### 7. Migration Already Exists

**Error:**
```
Migration table already exists
```

**Solution:**

```bash
# Option 1: Reset migrations
make fresh

# Option 2: Drop migration table
make shell-db
DROP TABLE migrations;
\q

make migrate
```

#### 8. Health Check Failing

**Error:**
```
Unhealthy: Health check failed
```

**Solution:**

```bash
# Check logs for specific service
docker compose logs app      # PHP
docker compose logs node     # Node
docker compose logs nginx    # Nginx
docker compose logs db       # PostgreSQL

# Manual health check
docker compose exec app php-fpm -t
docker compose exec nginx nginx -t
docker compose exec db pg_isready -U laravel
```

#### 9. Xdebug Not Working

**Error:** Breakpoints not hitting

**Solution:**

```bash
# Verify Xdebug is installed
make shell
php -v
# Should show: with Xdebug v3.4.6

# Check Xdebug config
php --ini | grep xdebug

# Verify port 9003 is exposed
docker compose ps
# app should show 0.0.0.0:9003->9003/tcp

# Test connection
telnet localhost 9003
```

#### 10. Out of Memory

**Error:**
```
Allowed memory size of X bytes exhausted
```

**Solution:**

```bash
# Increase memory in docker/php/php.ini
memory_limit = 512M

# Rebuild container
make build
make up
```

---

### Debugging Commands

```bash
# View logs (all services)
make logs

# View logs (specific service)
docker compose logs app
docker compose logs -f db        # Follow mode

# Execute commands in containers
docker compose exec app bash     # PHP container
docker compose exec node sh      # Node container
docker compose exec db psql -U laravel -d laravel

# Inspect container
docker compose inspect app

# Check resource usage
docker stats

# Network inspection
docker network inspect soundscape_laravel-network

# Volume inspection
docker volume inspect soundscape_postgres-data
```

---

### Reset Procedures

#### Soft Reset (Keep Database)
```bash
make down
make up
make composer-install
make yarn-install
```

#### Medium Reset (Fresh Database)
```bash
make fresh
```

#### Hard Reset (Remove All Caches)
```bash
make clean
make init
```

#### Nuclear Reset (Remove Everything)
```bash
make clean-docker
rm -rf vendor node_modules
make init
```

---

## 📚 Additional Resources

### Docker Documentation
- [Docker Compose](https://docs.docker.com/compose/)
- [Docker Multi-stage Builds](https://docs.docker.com/build/building/multi-stage/)
- [Docker Health Checks](https://docs.docker.com/engine/reference/builder/#healthcheck)

### Laravel + Docker
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Laravel Valet Docker](https://github.com/laravel/valet)

### Vite + Docker
- [Vite Docker Guide](https://vitejs.dev/guide/static-deploy.html)
- [HMR in Docker](https://vitejs.dev/config/server-options.html#server-hmr)

---

## 🤝 Contributing

When contributing Docker-related changes:

1. **Test thoroughly:**
   ```bash
   make clean-docker
   make init
   make test
   ```

2. **Document changes** in this file

3. **Update Makefile** if adding new workflows

4. **Test on multiple platforms** (macOS, Linux, Windows)

---

## 📝 Changelog

### 2025-01-23 - PHP 8.4 Upgrade
- Upgraded from PHP 8.3 to PHP 8.4
- Updated Xdebug to v3.4.6
- Verified all extensions compatible
- 552 tests passing

### 2025-01-15 - Initial Documentation
- Created comprehensive Docker documentation
- Documented all services and configurations
- Added troubleshooting guide

---

**Need help?** Check the [main README](README.md) or open an issue.

Built with ❤️ using Docker, Laravel, and modern development practices.
