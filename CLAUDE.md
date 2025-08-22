# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application using Livewire Volt for interactive components and Flux UI for design. The project is called "Soundscape" and is a modern portfolio/contact web application with a comprehensive admin dashboard, content management system, and contact form functionality.

## Key Technologies

- **Backend**: Laravel 12, PHP 8.3 (in Docker)
- **Frontend**: Livewire Volt, Flux UI, Tailwind CSS 4, Vite
- **Database**: PostgreSQL 15 (in Docker)
- **Testing**: Pest PHP with comprehensive coverage
- **Code Quality**: PHPStan (level 9), Laravel Pint
- **Package Management**: Composer (PHP), Yarn/NPM (JavaScript)
- **Infrastructure**: Docker & Docker Compose (development), Clever Cloud (production)

## IMPORTANT: Development Environment

**This application MUST be developed using Docker.** All commands should be run through Docker containers.

### Quick Start

```bash
# First time setup (automated)
make init

# Or manual setup
docker-compose up -d
make install

# Start development
make dev  # Starts containers and Vite
```

## Common Development Commands (Makefile Available!)

### Using Makefile (Recommended - Simplifies Docker Commands)

```bash
# Show all available commands
make help

# Start/stop environment
make up              # Start containers
make down            # Stop containers
make restart         # Restart containers

# Laravel commands
make artisan cmd="migrate"  # Run any artisan command
make migrate         # Run migrations
make seed            # Seed database
make fresh           # Fresh migration with seeding
make test            # Run tests
make pint            # Format code with Laravel Pint
make tinker          # Start Laravel Tinker

# Frontend commands
make vite            # Start Vite dev server
make vite-build      # Build for production
make yarn cmd="add package"  # Run any yarn command

# Access containers
make shell           # PHP container shell
make shell-node      # Node container shell
make shell-db        # Database shell
```

### Direct Docker Commands (Alternative)

```bash
# PHP/Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app composer install
docker-compose exec app ./vendor/bin/pint

# Node/Frontend commands  
docker-compose exec node yarn install
docker-compose exec node yarn dev --host

# Database access
docker-compose exec db psql -U laravel -d laravel
```

### Testing (Pest PHP)

```bash
# Run all tests
make test
# Or: docker-compose exec app php artisan test

# Run tests with coverage (HTML report generated)
make test-coverage

# Test by suite
make test-unit          # Unit tests only
make test-feature       # Feature tests only

# Advanced testing
make test-filter filter="contact"    # Filter by name
make test-file file=ContactTest      # Specific test file
make test-parallel                   # Parallel execution
make test-failed                     # Re-run failed tests only

# TDD workflow
make tdd                # Watch mode for continuous testing
make test-watch         # Alternative watch mode
make test-create name="Feature/NewTest"  # Create new test
```

### Code Quality

```bash
# Format PHP code with Laravel Pint
make pint
# Check without fixing
make pint-test

# Run PHPStan static analysis
make phpstan
# Generate PHPStan baseline
make phpstan-baseline

# Run all quality tools
make quality
# Check quality without fixing
make quality-check
```

### Database Operations

```bash
# Run migrations
make migrate

# Seed database
make seed

# Fresh migration with seeding
make fresh

# Database backup/restore
make db-backup
make db-restore file=backup.sql
```

### Access Points

- **Application**: http://localhost:8000
- **Vite Dev Server**: http://localhost:5173
- **PostgreSQL**: localhost:5432 (user: laravel, password: secret)

## Architecture

### Docker Infrastructure (Development)

The application runs in 4 optimized Docker containers:
1. **app** (laravel-app): PHP 8.3 FPM with Xdebug, running as non-root user
2. **node** (laravel-node): Node.js 20 Alpine for Vite/frontend development
3. **nginx** (laravel-nginx): Nginx Alpine web server (port 8000)
4. **db** (laravel-db): PostgreSQL 15 Alpine (port 5432 in dev only)

**Key Improvements:**
- Multi-stage builds for smaller images
- Non-root users for security
- Health checks on all services
- Optimized volumes for vendor/ and node_modules/
- Development tools included (Xdebug, hot-reload)

### Directory Structure

#### Domain-Driven Design Architecture

- **app/Application/**: Application services with DTOs
  - `Admin/`: Admin-related services (ContentManagementService, DashboardService)
  - `Portfolio/`: Portfolio services (ContactService, ContentService)
- **app/Domain/**: Domain logic and contracts
  - `Admin/`: Admin domain (Enums, Repositories interfaces)
- **app/Infra/**: Infrastructure implementations
  - `Repositories/Admin/`: Database repository implementations
- **app/Http/Controllers/**: HTTP controllers organized by domain
  - `Admin/`: Admin dashboard and content management
  - `Portfolio/`: Public portfolio pages (Home, About, Contact)
  - `Auth/`: Authentication controllers

#### Livewire & Views

- **app/Livewire/**: Livewire components and actions
  - `Admin/`: Admin components (Dashboard, ContentEdit, ContentList)
  - `Components/`: Reusable UI components (Navbar, Footer, Sections)
  - `Actions/`: Livewire actions (Logout)
- **app/Models/**: Eloquent models (User, PageContent, ContactMessage)
- **resources/views/livewire/**: Livewire Volt component views
  - `admin/`: Admin dashboard views
  - `auth/`: Authentication views (login, register, password reset)
  - `components/`: Reusable components (navbar, footer, sections)
  - `home/`: Homepage components
  - `settings/`: User settings pages
- **resources/views/**: Blade templates
  - `admin/`: Admin layout and pages
  - `portfolio/`: Public portfolio pages
  - `flux/`: Flux UI component overrides

#### Testing & Configuration

- **tests/**: Comprehensive test suite organized by architecture layers
  - `Feature/`: Feature tests organized by domain (Admin, Auth, Livewire, etc.)
  - `Unit/Application/`: Unit tests for application services and DTOs
  - `Infra/Repositories/`: Tests for infrastructure repository implementations
- **routes/web.php**: Uses both traditional routes and Volt routing
- **database/migrations/**: Database schema definitions
- **database/seeders/**: Sample data seeders
- **docker/**: Docker configuration files
  - `php/`: PHP configs, entrypoint script
  - `node/`: Node.js entrypoint script
  - `nginx/conf.d/`: Nginx configuration
  - `init-dev.sh`: Development initialization script

### Key Features & Patterns

1. **Admin Dashboard & Content Management**
   - Comprehensive admin interface at `/admin`
   - Content management system for dynamic page content
   - Dashboard with statistics and overview
   - Content editing with real-time preview
   - Protected with authentication middleware

2. **Contact System**
   - Contact form with GDPR compliance
   - Message persistence in `contact_messages` table
   - Admin interface for managing contact messages
   - Email validation and spam protection

3. **Livewire Volt Components**: Single-file components combining PHP logic and Blade templates
   - Components are in `resources/views/livewire/`
   - Routes defined with `Volt::route()` in `routes/web.php`
   - Admin components for dashboard and content management

4. **Authentication**: Standard Laravel authentication with Livewire forms
   - Protected routes use `auth` middleware
   - Settings pages require authentication
   - Admin routes require authentication

5. **Content Management**: PageContent model stores dynamic page content
   - Seeded with PageContentSeeder
   - Keys like 'home_text', 'about_section_1', etc.
   - Admin interface for editing content

6. **Frontend Build**: Vite handles asset compilation
   - Entry points: `resources/css/app.css`, `resources/js/app.js`
   - Tailwind CSS 4 with Vite plugin
   - HMR configured for Docker development
   - Admin-specific styles in `admin-enhancements.css`

7. **Testing Structure**: Pest PHP for comprehensive testing
   - Feature tests use RefreshDatabase trait
   - Tests organized by architecture layers
   - High coverage including admin functionality

## Database Schema

Key tables:
- **users**: Standard Laravel user authentication
- **page_contents**: Dynamic page content (key, content, title, page)
- **contact_messages**: Contact form submissions with GDPR compliance
  - Fields: name, email, subject, message, gdpr_consent, read_at
  - Indexed on email and created_at for performance
- **cache**, **jobs**, **sessions**: Laravel framework tables

## Important Files

- **Makefile**: Simplified Docker commands (run `make help` to see all)
- **docker-compose.yml**: Main Docker services configuration
- **docker-compose.override.yml**: Local development overrides
- **Dockerfile**: Optimized multi-stage PHP container
- **Dockerfile.node**: Optimized Node.js container
- **.dockerignore**: Excludes unnecessary files from Docker build
- **composer.json**: PHP dependencies
- **package.json**: JavaScript dependencies (Vite, Tailwind)
- **vite.config.js**: Vite configuration with HMR support
- **.env.docker**: Pre-configured environment for Docker development