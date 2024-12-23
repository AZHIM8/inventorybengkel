FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
&& docker-php-ext-configure gd --with-freetype --with-jpeg \
&& docker-php-ext-install gd zip pdo pdo_mysql exif \
&& docker-php-ext-enable exif

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --installdir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-scripts --no-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get install -y nodejs

# Install Node.js dependencies
RUN npm install

# Build assets
RUN npm run build # Atau gunakan npm run dev jika tidak ingin build

# Expose port
EXPOSE 80 9000

# Start PHP-FPM
CMD ["php-fpm"]
