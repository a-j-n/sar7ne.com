#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# Define variables
APP_DIR="/var/www/html/sar7ne"
ENV_FILE=".env"
SERVER_ENV_FILE=".env.server"
BACKUP_ENV_FILE=".env.backup"

# Navigate to the application directory
cd "$APP_DIR"

# Ensure the script is run with appropriate privileges
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root"
   exit 1
fi

# Export Composer settings
export COMPOSER_ALLOW_SUPERUSER=1

# Check for required tools
for tool in git composer yarn; do
    if ! command -v $tool &> /dev/null; then
        echo "$tool is not installed. Aborting deployment."
        exit 1
    fi
done

# Backup existing .env file
if [ -f "$ENV_FILE" ]; then
    cp "$ENV_FILE" "$BACKUP_ENV_FILE"
    echo "Backup of .env file created as $BACKUP_ENV_FILE"
fi

# Manage environment file
if [ -f "$SERVER_ENV_FILE" ]; then
    rm -f "$ENV_FILE"
    cp "$SERVER_ENV_FILE" "$ENV_FILE"
else
    echo "$SERVER_ENV_FILE does not exist. Aborting deployment."
    exit 1
fi

# Enable maintenance mode
php artisan down || true

# Pull the latest changes from the repository
if ! git diff-index --quiet HEAD --; then
    echo "Uncommitted changes detected. Please commit or stash them before deploying."
    exit 1
fi
git pull origin main --no-ff
git reset --hard origin/main

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Run migrations and seeders
php artisan migrate --seed --force

# Clear and cache configurations
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan clear

# Set permissions
chmod -R 775 storage public bootstrap/cache

# Check Yarn and Node.js versions
echo "Yarn version: $(yarn --version)"
echo "Node.js version: $(node --version)"

# Install and build frontend assets
yarn install --frozen-lockfile
yarn build

# Final cache clear to ensure everything is up-to-date
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Disable maintenance mode
php artisan up

# Log deployment completion with timestamp
echo "Deployment completed successfully at $(date)"
