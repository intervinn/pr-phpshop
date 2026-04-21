FROM php:8.5-apache
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    zip \
    unzip \
    git

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql soap xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files and install
COPY src/composer.json ./
RUN composer install --no-interaction --optimize-autoloader
EXPOSE 80
