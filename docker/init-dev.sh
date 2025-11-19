#!/bin/bash
set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Initializing Laravel Development Environment${NC}"

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}❌ Docker is not running. Please start Docker and try again.${NC}"
    exit 1
fi

# Check if .env file exists
if [ ! -f .env ]; then
    echo -e "${YELLOW}📝 Creating .env file from .env.docker${NC}"
    if [ -f .env.docker ]; then
        cp .env.docker .env
    else
        cp .env.example .env
        # Update database configuration for Docker
        sed -i '' 's/DB_CONNECTION=sqlite/DB_CONNECTION=pgsql/' .env 2>/dev/null || sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=pgsql/' .env
        sed -i '' 's/DB_HOST=127.0.0.1/DB_HOST=db/' .env 2>/dev/null || sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/' .env
        sed -i '' 's/# DB_PORT=/DB_PORT=5432/' .env 2>/dev/null || sed -i 's/# DB_PORT=/DB_PORT=5432/' .env
        sed -i '' 's/# DB_DATABASE=/DB_DATABASE=laravel/' .env 2>/dev/null || sed -i 's/# DB_DATABASE=/DB_DATABASE=laravel/' .env
        sed -i '' 's/# DB_USERNAME=/DB_USERNAME=laravel/' .env 2>/dev/null || sed -i 's/# DB_USERNAME=/DB_USERNAME=laravel/' .env
        sed -i '' 's/# DB_PASSWORD=/DB_PASSWORD=secret/' .env 2>/dev/null || sed -i 's/# DB_PASSWORD=/DB_PASSWORD=secret/' .env
    fi
fi

# Build and start containers
echo -e "${YELLOW}🔨 Building Docker containers...${NC}"
docker-compose build --no-cache

echo -e "${YELLOW}🚀 Starting Docker containers...${NC}"
docker-compose up -d

# Wait for database to be ready
echo -e "${YELLOW}⏳ Waiting for database to be ready...${NC}"
until docker-compose exec -T db pg_isready -U laravel > /dev/null 2>&1; do
    sleep 1
    echo -n "."
done
echo ""

# Install PHP dependencies
echo -e "${YELLOW}📦 Installing PHP dependencies...${NC}"
docker-compose exec app composer install

# Generate application key if needed
if ! grep -q "APP_KEY=base64:" .env; then
    echo -e "${YELLOW}🔑 Generating application key...${NC}"
    docker-compose exec app php artisan key:generate
fi

# Run migrations
echo -e "${YELLOW}🗄️ Running database migrations...${NC}"
docker-compose exec app php artisan migrate

# Seed database
echo -e "${YELLOW}🌱 Seeding database...${NC}"
docker-compose exec app php artisan db:seed

# Install Node dependencies (entrypoint handles permissions automatically)
echo -e "${YELLOW}📦 Installing Node.js dependencies...${NC}"
docker-compose exec node yarn install

# Publish assets
echo -e "${YELLOW}📋 Publishing Livewire and Flux assets...${NC}"
docker-compose exec app php artisan livewire:publish --assets
docker-compose exec app php artisan vendor:publish --tag=flux-assets --force

# Create storage link
echo -e "${YELLOW}🔗 Creating storage link...${NC}"
docker-compose exec app php artisan storage:link

# Clear caches
echo -e "${YELLOW}🧹 Clearing caches...${NC}"
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan cache:clear

echo -e "${GREEN}✅ Development environment is ready!${NC}"
echo ""
echo -e "${GREEN}📌 Access points:${NC}"
echo -e "  • Application: ${GREEN}http://localhost:8000${NC}"
echo -e "  • Vite Dev Server: ${GREEN}http://localhost:5173${NC}"
echo -e "  • Database: ${GREEN}localhost:5432${NC} (user: laravel, password: secret)"
echo ""
echo -e "${YELLOW}🎯 Quick commands:${NC}"
echo -e "  • Start Vite: ${GREEN}docker-compose exec node yarn dev --host${NC}"
echo -e "  • Run tests: ${GREEN}docker-compose exec app php artisan test${NC}"
echo -e "  • View logs: ${GREEN}docker-compose logs -f${NC}"
echo -e "  • Stop containers: ${GREEN}docker-compose down${NC}"
echo ""
echo -e "${YELLOW}💡 Tip: Use 'make help' to see all available commands${NC}"