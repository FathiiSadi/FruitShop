# Stage 1: Build dependencies
FROM composer:latest as build-stage
WORKDIR /app
COPY composer.json composer.lock ./
# Install dependencies in a clean environment
RUN composer install --no-interaction --optimize-autoloader --no-scripts --prefer-dist --ignore-platform-reqs

# Stage 2: Final image
FROM php:8.2-apache
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV YII_ENV=prod
ENV YII_DEBUG=0

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo_pgsql \
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

WORKDIR /var/www/html

# Copy application code
COPY . .

# Copy vendors from build stage
COPY --from=build-stage /app/vendor/ /var/www/html/vendor/

# Configure Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/web
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set permissions
RUN mkdir -p runtime assets web/assets && \
    chmod -R 777 runtime assets web/assets

EXPOSE 80
CMD ["apache2-foreground"]
