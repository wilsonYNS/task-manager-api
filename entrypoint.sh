#!/bin/sh

# Setup .env if missing
if [ ! -f .env ]; then
    echo ".env not found, copying from .env.dev"
    cp .env.dev .env
fi

#Install dependencies if vendor doesn't exist
if [ ! -f vendor ]; then
    echo "Installing composer dependencies..."
    sudo -u www-data composer install --no-dev --optimize-autoloader --no-progress --no-interaction
fi

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
timeout 30s sh -c 'until nc -z mysql 3306; do sleep 1; done'

# Run migrations and seed database
php artisan migrate:fresh --seed

exec php-fpm
