# Dockerfile
FROM php:8.2-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libonig-dev libzip-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy existing app files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

EXPOSE 80

