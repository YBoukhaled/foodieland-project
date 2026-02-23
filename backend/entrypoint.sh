#!/bin/sh

# Create .env from .env.example if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Wait until MySQL is actually ready to accept connections
echo "Waiting for database to be ready..."
until php -r "new PDO('mysql:host=db;port=3306;dbname=foodieland', 'root', 'root');" 2>/dev/null; do
    echo "Database not ready yet - retrying in 3 seconds..."
    sleep 3
done
echo "Database is ready!"

echo "Running fresh migrations and seeders..."
php artisan migrate:fresh --seed --force

echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8000
