#!/bin/sh

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
timeout 30s sh -c 'until nc -z mysql 3306; do sleep 1; done'

# Run migrations and seed database
php artisan migrate:fresh --seed

exec php-fpm
