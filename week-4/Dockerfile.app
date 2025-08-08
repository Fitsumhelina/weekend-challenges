FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql

# Add composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create non-root user
RUN useradd -m -d /home/laravel -s /bin/bash laravel

# Set workdir
WORKDIR /var/www/html

# Copy project
COPY . .

# Set permissions
RUN chown -R laravel:laravel /var/www/html

# Switch to non-root user
USER laravel
