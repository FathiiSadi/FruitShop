# Use the official PHP 8.2 image with Apache
FROM php:8.2-apache

# Set environment variables
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

# Install system dependencies
# Including git as it is often required for composer dependencies
# Including libonig-dev and libxml2-dev for common PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    zip \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
# Removed 'curl' from docker-php-ext-install as it is usually built-in, 
# added mbstring and bcmath properly.
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    gd \
    zip \
    intl \
    bcmath \
    mbstring \
    exif \
    pcntl \
    dom \
    xml \
    fileinfo

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy the application code
COPY . .

# Configure Apache DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set permissions for Yii2 directories
# Ensure directories exist and have correct permissions for the apache user/group
RUN mkdir -p runtime assets web/assets && \
    chmod -R 777 runtime assets web/assets

# Install PHP dependencies
# Added --prefer-dist to minimize git usage and speed up install
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts --prefer-dist

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
