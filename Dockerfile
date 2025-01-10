# Use an official PHP 7.4 image
FROM php:7.4-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    wget \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    libonig-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the existing application directory contents to the working directory
COPY . /var/www

# Copy the existing application directory permissions to the working directory
COPY --chown=www-data:www-data . /var/www

# Install composer dependencies
RUN composer install --no-dev --prefer-dist --optimize-autoloader

USER www-data

EXPOSE 9000
CMD php -S 0.0.0.0:9000