# Use PHP base image
FROM php:8.2.12-fpm

# Install necessary dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    maven \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required for Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Set working directory
WORKDIR /var/www

# Copy all project files into the container
COPY . .

# Set file permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# Expose necessary ports
EXPOSE 80 9000

# Default command to start PHP-FPM and Nginx
CMD ["php-fpm"]
