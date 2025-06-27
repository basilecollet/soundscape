# Project Guidelines

This document provides guidelines for development on this Laravel Livewire project.

## Core Development Principles

### Frontend Development
- **Mobile-First Approach**: All UI development must follow a mobile-first methodology. Design and implement for mobile devices first, then progressively enhance for larger screens.
- **Livewire Volt with TailwindCSS**: Use Livewire Volt for reactive components with TailwindCSS for styling.

### Backend Development
- **Single Responsibility Principle**: Each class should have only one reason to change. Keep classes focused on a single responsibility.
- **Separation of Concerns**: Clearly separate different aspects of the application (business logic, presentation, data access) into distinct sections.

### Testing Requirements
- **Test-Driven Development**: All new features must be accompanied by appropriate tests. No feature is considered complete without tests.

## Docker Setup

### Prerequisites
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Container Structure
This project uses four Docker containers:
1. **app** - PHP 8.3 FPM container for the Laravel backend
2. **node** - Node.js container for the frontend
3. **nginx** - Nginx web server
4. **db** - PostgreSQL 15 database

### Getting Started with Docker

1. **Start the Docker environment**:
   ```bash
   docker-compose up -d
   ```

2. **Configure Laravel for PostgreSQL**:
   ```bash
   cp .env.docker .env
   ```

3. **Install dependencies and run migrations**:
   ```bash
   # Install PHP dependencies
   docker-compose exec app composer install

   # Install JavaScript dependencies
   docker-compose exec node yarn install

   # Run database migrations
   docker-compose exec app php artisan migrate

   # Generate application key (if needed)
   docker-compose exec app php artisan key:generate

   # Publish Livewire assets
   docker-compose exec app php artisan livewire:publish --assets
   ```

### Docker Development Workflow

1. Start the Docker environment: `docker-compose up -d`
2. Start the frontend development server: `docker-compose exec node yarn dev --host`
3. Access your application at http://localhost:8000
4. Access your frontend development server at http://localhost:5173

### Common Docker Commands

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View container logs
docker-compose logs

# Run Artisan commands
docker-compose exec app php artisan <command>

# Run frontend commands
docker-compose exec node yarn <command>
```

### Alternative Local Setup (without Docker)

If you prefer not to use Docker, you can set up the project locally:

#### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm/yarn

#### Initial Setup
1. Clone the repository
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install JavaScript dependencies:
   ```bash
   npm install
   # or
   yarn install
   ```
4. Create environment file:
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Set up your database (default is SQLite):
   ```bash
   touch database/database.sqlite
   ```
7. Run migrations:
   ```bash
   php artisan migrate
   ```

#### Local Development Environment
The project includes a convenient development script:

```bash
composer dev
```

This command runs:
- Laravel development server
- Queue worker
- Log watcher
- Vite development server for frontend assets

#### Production Build
To build assets for production:

```bash
npm run build
# or
yarn build
```

## Testing Information

### Testing Configuration
- Tests use SQLite in-memory database
- The project uses Pest PHP, a testing framework built on top of PHPUnit
- Tests are located in the `tests` directory, divided into:
  - `tests/Feature`: Application feature tests
  - `tests/Unit`: Unit tests for individual components

### Running Tests
Run all tests:
```bash
php artisan test
```

Run a specific test file:
```bash
php artisan test --filter=SimpleTest
```

Run tests with coverage report (requires Xdebug):
```bash
php artisan test --coverage
```

### Creating Tests
1. Create a new test file in the appropriate directory:
   - `tests/Feature` for feature tests
   - `tests/Unit` for unit tests

2. Use Pest PHP syntax for writing tests:

```php
<?php

test('description of what is being tested', function () {
    // Arrange - set up the test

    // Act - perform the action

    // Assert - verify the result
    expect(true)->toBeTrue();
});
```

3. For tests requiring database access, use the RefreshDatabase trait:

```php
<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('authenticated users can access the dashboard', function () {
    // Create a user using the factory
    $user = User::factory()->create();

    // Act as the user
    $this->actingAs($user);

    // Make a request to the dashboard
    $response = $this->get('/dashboard');

    // Assert the response is successful
    $response->assertStatus(200);
});
```

## Additional Development Information

### Code Style
The project uses Laravel Pint for code style enforcement. Run:

```bash
./vendor/bin/pint
```

### Frontend Assets
- The project uses Vite for asset compilation
- TailwindCSS is used for styling
- JavaScript modules are located in `resources/js`
- CSS files are located in `resources/css`

### Database
- The default configuration uses SQLite for simplicity
- Database migrations are in `database/migrations`
- Factories for test data are in `database/factories`
- Seeders for populating the database are in `database/seeders`

### Authentication
The project uses Laravel's built-in authentication system with Livewire components.

### Livewire Components
- Livewire components are located in `app/Livewire`
- The project uses Volt, a Blade-like syntax for Livewire components

### Debugging
For debugging, the project includes Laravel Pail, which provides a real-time log viewer:

```bash
php artisan pail
```
