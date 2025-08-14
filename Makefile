.PHONY: help init up down restart build logs shell test migrate seed fresh artisan composer npm yarn vite clean

# Variables
DC = docker-compose
APP = $(DC) exec app
NODE = $(DC) exec node
DB = $(DC) exec db

# Colors
GREEN = \033[0;32m
YELLOW = \033[1;33m
RED = \033[0;31m
NC = \033[0m # No Color

## Help command
help: ## Show this help
	@echo "$(GREEN)Available commands:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(YELLOW)%-15s$(NC) %s\n", $$1, $$2}'

## Docker commands
init: ## Initialize development environment
	@bash docker/init-dev.sh

up: ## Start all containers
	$(DC) up -d
	@echo "$(GREEN)✅ Containers started$(NC)"

down: ## Stop all containers
	$(DC) down
	@echo "$(RED)🛑 Containers stopped$(NC)"

restart: ## Restart all containers
	$(DC) restart
	@echo "$(GREEN)🔄 Containers restarted$(NC)"

build: ## Rebuild containers
	$(DC) build --no-cache
	@echo "$(GREEN)🔨 Containers rebuilt$(NC)"

logs: ## View container logs
	$(DC) logs -f

ps: ## Show container status
	$(DC) ps

## Application commands
shell: ## Access PHP container shell
	$(APP) bash

shell-node: ## Access Node container shell
	$(NODE) sh

shell-db: ## Access database shell
	$(DB) psql -U laravel -d laravel

## Laravel commands
artisan: ## Run artisan command (usage: make artisan cmd="migrate")
	$(APP) php artisan $(cmd)

migrate: ## Run database migrations
	$(APP) php artisan migrate

rollback: ## Rollback last migration
	$(APP) php artisan migrate:rollback

seed: ## Seed the database
	$(APP) php artisan db:seed

fresh: ## Fresh migration with seeding
	$(APP) php artisan migrate:fresh --seed

test: ## Run tests
	$(APP) php artisan test

test-coverage: ## Run tests with coverage
	$(APP) php artisan test --coverage

pint: ## Run Laravel Pint
	$(APP) ./vendor/bin/pint

pint-test: ## Test Laravel Pint without fixing
	$(APP) ./vendor/bin/pint --test

tinker: ## Start Laravel Tinker
	$(APP) php artisan tinker

queue: ## Start queue worker
	$(APP) php artisan queue:work

cache-clear: ## Clear all caches
	$(APP) php artisan cache:clear
	$(APP) php artisan config:clear
	$(APP) php artisan view:clear
	$(APP) php artisan route:clear
	@echo "$(GREEN)🧹 Caches cleared$(NC)"

## Composer commands
composer: ## Run composer command (usage: make composer cmd="require package")
	$(APP) composer $(cmd)

composer-install: ## Install PHP dependencies
	$(APP) composer install

composer-update: ## Update PHP dependencies
	$(APP) composer update

composer-dump: ## Dump composer autoload
	$(APP) composer dump-autoload

## Node commands
npm: ## Run npm command (usage: make npm cmd="install package")
	$(NODE) npm $(cmd)

yarn: ## Run yarn command (usage: make yarn cmd="add package")
	$(NODE) yarn $(cmd)

yarn-install: ## Install Node dependencies
	$(NODE) yarn install

vite: ## Start Vite dev server
	$(NODE) yarn dev --host

vite-build: ## Build assets for production
	$(NODE) yarn build

## Database commands
db-backup: ## Backup database
	$(DB) pg_dump -U laravel laravel > backups/backup_$$(date +%Y%m%d_%H%M%S).sql
	@echo "$(GREEN)💾 Database backed up$(NC)"

db-restore: ## Restore database (usage: make db-restore file=backup.sql)
	cat $(file) | $(DB) psql -U laravel -d laravel
	@echo "$(GREEN)♻️ Database restored$(NC)"

## Cleanup commands
clean: ## Clean up everything (containers, volumes, caches)
	$(DC) down -v
	rm -rf vendor node_modules
	rm -rf storage/logs/*.log
	rm -rf bootstrap/cache/*.php
	@echo "$(RED)🧹 Everything cleaned up$(NC)"

clean-docker: ## Remove all Docker artifacts
	$(DC) down -v --rmi all
	@echo "$(RED)🐳 Docker artifacts removed$(NC)"

## Development helpers
dev: ## Start development environment with Vite
	@make up
	@make vite

stop: ## Stop development environment
	@make down

status: ## Show full status
	@echo "$(GREEN)📊 Container Status:$(NC)"
	@$(DC) ps
	@echo ""
	@echo "$(GREEN)🔍 Laravel Status:$(NC)"
	@$(APP) php artisan about || true

install: ## Full installation
	@make build
	@make up
	@make composer-install
	@make yarn-install
	@make migrate
	@make seed
	@echo "$(GREEN)✅ Installation complete!$(NC)"