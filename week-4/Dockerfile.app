FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql

# Add user (UID 1000 = same as host default user)
RUN useradd -u 1000 -m laraveluser

# Set working directory
WORKDIR /var/www/html

# Copy Composer from base image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy all project files
COPY . .

# Give permissions to non-root user
RUN chown -R laraveluser:laraveluser /var/www/html

# Switch to non-root user
USER laraveluser
