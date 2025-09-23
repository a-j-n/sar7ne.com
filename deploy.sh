#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# Define variables
APP_DIR="/var/www/html/sar7ne"
ENV_FILE=".env"
SERVER_ENV_FILE=".env.server"

# Navigate to the application directory
cd "$APP_DIR"

# Ensure the script is run with appropriate privileges
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root"
   exit 1
fi

# Export Composer settings
export COMPOSER_ALLOW_SUPERUSER=1

# Manage environment file
if [ -f "$SERVER_ENV_FILE" ]; then
    rm -f "$ENV_FILE"
    cp "$SERVER_ENV_FILE" "$ENV_FILE"
else
    echo "$SERVER_ENV_FILE does not exist. Aborting deployment."
    exit 1
fi

# Pull the latest changes from the repository
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
sudo chmod -R 775  public bootstrap/cache
sudo chmod -R 777 storage public/build
# Install and build frontend assets
yarn install --frozen-lockfile
yarn build

# Final cache clear to ensure everything is up-to-date
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Log deployment completion
echo "Deployment completed successfully."
