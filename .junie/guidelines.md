# Project Guidelines

This document provides guidelines for development on this Laravel Livewire project.

## Build/Configuration Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm

### Initial Setup
1. Clone the repository
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install JavaScript dependencies:
   ```bash
   npm install
   ```
4. Create environment file:
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Create SQLite database:
   ```bash
   touch database/database.sqlite
   ```
7. Run migrations:
   ```bash
   php artisan migrate
   ```

### Development Environment
The project includes a convenient development script that starts all necessary services:

```bash
composer dev
```

This command runs:
- Laravel development server
- Queue worker
- Log watcher
- Vite development server for frontend assets

Alternatively, you can run individual services:

1. Start the Laravel development server:
   ```bash
   php artisan serve
   ```

2. Compile frontend assets:
   ```bash
   npm run dev
   ```

### Production Build
To build assets for production:

```bash
npm run build
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
