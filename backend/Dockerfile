FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions for MySQL
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first (better layer caching)
COPY composer.json composer.lock ./

# Install dependencies during build
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy the rest of the application
COPY . .

# Copy and set permissions on entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/entrypoint.sh"]
