# Docker Setup Documentation

This document explains how to use Docker with this Laravel project.

## Prerequisites

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Container Structure

This project uses four Docker containers:

1. **app** - PHP 8.3 FPM container for the Laravel backend
2. **node** - Node.js container for the frontend
3. **nginx** - Nginx web server
4. **db** - PostgreSQL 15 database

## Getting Started

### 1. Initialize the Docker Environment

```bash
# Build and start the containers
docker-compose up -d

# View running containers
docker-compose ps
```

### 2. Configure Laravel for PostgreSQL

Copy the provided Docker environment file:

```bash
cp .env.docker .env
```

This file is already configured with the correct database settings:

```
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

Alternatively, you can manually update your existing `.env` file with these settings.

### 3. Install Dependencies and Run Migrations

```bash
# Install PHP dependencies
docker-compose exec app composer install

# Install JavaScript dependencies
docker-compose exec node yarn install

# Run database migrations
docker-compose exec app php artisan migrate

# Generate application key (if needed)
docker-compose exec app php artisan key:generate
```

## Common Commands

### Container Management

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# Restart containers
docker-compose restart

# View container logs
docker-compose logs

# View logs for a specific container
docker-compose logs app
```

### Backend Commands

```bash
# Run Artisan commands
docker-compose exec app php artisan <command>

# Example: Clear cache
docker-compose exec app php artisan cache:clear

# Run tests
docker-compose exec app php artisan test
```

### Frontend Commands

```bash
# Install JavaScript dependencies (if not already done)
docker-compose exec node yarn install

# Start the development server
docker-compose exec node yarn dev

# Build for production
docker-compose exec node yarn build

# Run linting
docker-compose exec node yarn lint
```

### Database Management

```bash
# Access PostgreSQL CLI
docker-compose exec db psql -U laravel -d laravel

# Create a database backup
docker-compose exec db pg_dump -U laravel laravel > backup.sql

# Restore from a backup
cat backup.sql | docker-compose exec -T db psql -U laravel -d laravel
```

## Development Workflow

1. Start the Docker environment: `docker-compose up -d`
2. Install JavaScript dependencies (if not already done): `docker-compose exec node yarn install`
3. Start the frontend development server: `docker-compose exec node yarn dev`
4. Make changes to your code
5. Access your backend application at http://localhost:8000
6. Access your frontend development server at http://localhost:5173
7. Run backend tests: `docker-compose exec app php artisan test`
8. When finished, stop the environment: `docker-compose down`

## Troubleshooting

### Permission Issues

If you encounter permission issues with storage or cache directories:

```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Database Connection Issues

If the application can't connect to the database:

1. Ensure the database container is running: `docker-compose ps`
2. Check the database logs: `docker-compose logs db`
3. Verify your `.env` configuration matches the docker-compose.yml settings

### Container Build Issues

If you need to rebuild the containers:

```bash
docker-compose build --no-cache
docker-compose up -d
```

### Frontend Development Issues

If you encounter issues with the Node.js container:

1. Check if the container is running: `docker-compose ps node`
2. View the Node.js logs: `docker-compose logs node`
3. Restart the Node.js container: `docker-compose restart node`
4. Make sure you've installed dependencies: `docker-compose exec node yarn install`
5. If you get an error about the container restarting, wait a moment for it to stabilize
6. If Vite is not detecting file changes, you may need to add the `--force` flag: 
   ```bash
   docker-compose exec node yarn dev --force
   ```
