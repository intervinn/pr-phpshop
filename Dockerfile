FROM php:8.5-fpm-alpine

RUN apk add --no-cache \
    libxml2-dev \
    zip \
    unzip \
    git \
    linux-headers

RUN docker-php-ext-install pdo_mysql soap xml

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-interaction --no-dev

RUN echo "auto_prepend_file=/var/www/html/vendor/autoload.php" > /usr/local/etc/php/conf.d/autoprepend.ini

EXPOSE 9000
CMD ["php-fpm"]