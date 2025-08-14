# 🎵 Soundscape Audio

A modern web application for Soundscape Audio, built with Laravel 12, Livewire Volt, and Tailwind CSS. This application provides a professional web presence for an audio equipment and services company.

![Laravel](https://img.shields.io/badge/Laravel-v12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3.0-FB70A9?style=for-the-badge&logo=livewire&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Quick Start](#-quick-start)
- [Development](#-development)
- [Project Structure](#-project-structure)
- [Available Commands](#-available-commands)
- [Testing](#-testing)
- [Deployment](#-deployment)
- [Contributing](#-contributing)
- [License](#-license)

## ✨ Features

### Current Features
- 🏠 **Dynamic Homepage** - Customizable content sections with CMS
- 🔐 **User Authentication** - Complete auth system with registration, login, password reset
- 👤 **User Dashboard** - Profile management and settings
- 📱 **Responsive Design** - Mobile-first approach with Tailwind CSS
- 🎨 **Modern UI** - Flux UI components with smooth animations
- 🌐 **SEO Ready** - Clean URLs and meta tag support
- 🐳 **Docker Development** - Optimized containerized environment

### Sections
- **Home** - Hero section with animated SVG globe
- **About** - Three-column layout with content management
- **Contact** - Contact form (frontend ready)
- **Dashboard** - User area with settings management

## 🛠 Tech Stack

### Backend
- **Laravel 12** - Latest PHP framework
- **Livewire Volt** - Single-file reactive components
- **PostgreSQL** - Primary database (Docker)
- **PHP 8.3** - With OPcache and Xdebug

### Frontend
- **Tailwind CSS 4** - Utility-first CSS framework
- **Flux UI** - Component library
- **Alpine.js** - Lightweight JavaScript framework
- **Vite** - Fast build tool with HMR

### Infrastructure
- **Docker** - Containerized development
- **Nginx** - Web server
- **Make** - Task automation
- **Pest PHP** - Testing framework

## 📦 Prerequisites

### For Docker Development (Recommended)
- Docker Desktop 20.10+ or Docker Engine with Docker Compose
- Make (optional but recommended)
- 4GB RAM minimum for Docker
- 10GB free disk space

### For Local Development (Alternative)
- PHP 8.3+
- Composer 2.0+
- Node.js 20+ and NPM/Yarn
- PostgreSQL 15+ or SQLite

## 🚀 Installation

### Option 1: Quick Start with Docker (Recommended)

```bash
# Clone the repository
git clone https://github.com/yourusername/soundscape.git
cd soundscape

# Make initialization script executable
chmod +x docker/init-dev.sh

# Run automated setup (installs everything)
make init

# Start development server
make dev
```

That's it! The application will be available at:
- 🌐 **Application**: http://localhost:8000
- ⚡ **Vite Dev Server**: http://localhost:5173
- 🗄️ **Database**: localhost:5432

### Option 2: Manual Docker Setup

```bash
# Clone the repository
git clone https://github.com/yourusername/soundscape.git
cd soundscape

# Copy environment file
cp .env.docker .env

# Build and start containers
docker-compose up -d

# Install dependencies
make composer-install
make yarn-install

# Generate application key
docker-compose exec app php artisan key:generate

# Run migrations and seeders
make fresh

# Start Vite dev server
make vite
```

### Option 3: Local Development (Without Docker)

```bash
# Clone the repository
git clone https://github.com/yourusername/soundscape.git
cd soundscape

# Install PHP dependencies
composer install

# Copy environment file and configure
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env file
# DB_CONNECTION=sqlite (or pgsql/mysql)

# Run migrations and seeders
php artisan migrate --seed

# Install Node dependencies
npm install

# Start development servers
# Terminal 1:
php artisan serve

# Terminal 2:
npm run dev
```

## 💻 Development

### Daily Workflow

```bash
# Start your day
make up        # Start Docker containers
make vite      # Start Vite dev server (in another terminal)

# During development
make artisan cmd="make:model Product -m"  # Create models
make migrate                               # Run migrations
make test                                  # Run tests
make pint                                  # Format code

# End of day
make down      # Stop containers
```

### Available Make Commands

Run `make help` to see all available commands:

| Command | Description |
|---------|-------------|
| `make init` | Complete project initialization |
| `make dev` | Start development environment |
| `make test` | Run tests |
| `make pint` | Format PHP code |
| `make fresh` | Fresh migration with seeding |
| `make shell` | Access PHP container |
| `make logs` | View container logs |

### Code Style

The project uses Laravel Pint for PHP code formatting:

```bash
# Format code
make pint

# Check without fixing
make pint-test
```

## 📁 Project Structure

```
soundscape/
├── app/
│   ├── Http/          # HTTP layer
│   ├── Livewire/      # Livewire components
│   └── Models/        # Eloquent models
├── database/
│   ├── migrations/    # Database migrations
│   └── seeders/       # Data seeders
├── docker/            # Docker configuration
│   ├── php/          # PHP configs
│   ├── node/         # Node configs
│   └── nginx/        # Nginx configs
├── resources/
│   ├── css/          # Stylesheets
│   ├── js/           # JavaScript
│   └── views/        # Blade templates
│       └── livewire/ # Livewire components
├── routes/           # Application routes
├── tests/            # Test files
├── .env.example      # Environment template
├── docker-compose.yml # Docker services
├── Makefile          # Task automation
└── README.md         # This file
```

## 🧪 Testing

The project uses Pest PHP for testing:

```bash
# Run all tests
make test

# Run with coverage
make test-coverage

# Run specific test
docker-compose exec app php artisan test tests/Feature/DashboardTest.php
```

### Current Test Coverage
- ✅ Authentication flows
- ✅ Dashboard access
- ✅ Profile management
- ⚠️ Homepage components (needs implementation)
- ⚠️ Contact form (needs implementation)

## 🚢 Deployment

### Production with Clever Cloud

The application is configured for deployment on Clever Cloud. Configuration will be added when instances are ready.

### Environment Variables

Key environment variables for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Mail configuration
MAIL_MAILER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=587
MAIL_USERNAME=your-mail-username
MAIL_PASSWORD=your-mail-password
```

## 🐛 Troubleshooting

### Common Issues

#### Port Already in Use
```bash
# Find process using port
lsof -i :8000

# Or change port in docker-compose.override.yml
```

#### Permission Issues
```bash
docker-compose exec app chown -R www:www storage bootstrap/cache
```

#### Database Connection Failed
```bash
# Check database status
docker-compose ps db

# View logs
docker-compose logs db
```

#### Reset Everything
```bash
make clean-docker  # Remove all Docker artifacts
make init         # Start fresh
```

For more troubleshooting, see [docker.md](docker.md).

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Use conventional commits

## 📝 Documentation

- [Docker Setup](docker.md) - Detailed Docker documentation
- [Claude AI Guide](CLAUDE.md) - AI assistant guidelines
- [Laravel Docs](https://laravel.com/docs) - Framework documentation

## 📄 License

This project is proprietary software. All rights reserved.

## 👥 Team

- **Development** - Basile Collet
- **Framework** - Laravel by Taylor Otwell
- **UI Components** - Flux by Caleb Porzio

## 🔗 Links

- **Production** - Coming soon
- **Documentation** - [docker.md](docker.md)
- **Issues** - GitHub Issues

---

Built with ❤️ using Laravel, Livewire, and modern web technologies.