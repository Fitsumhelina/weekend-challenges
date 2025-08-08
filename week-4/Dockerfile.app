# Use official PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql

# Add Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy only essential files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html
