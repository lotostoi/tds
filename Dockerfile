FROM php:8.2-fpm
# Базовый образ с PHP 8.2 и FastCGI Process Manager для обработки PHP запросов

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    curl \
    git \
    bash


RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


WORKDIR /var/www

COPY . .

RUN composer install --optimize-autoloader

RUN cp .env.example .env && php artisan key:generate

RUN chmod -R 777 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
