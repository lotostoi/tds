FROM php:8.2-fpm
# Базовый образ с PHP 8.2 и FastCGI Process Manager для обработки PHP запросов

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    bash \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_mysql pcntl gd

RUN pecl install redis \
    && docker-php-ext-enable redis


RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader

RUN cp .env.example .env && php artisan key:generate

RUN chown -R www-data:www-data /var/www/html/storage \
    && chmod -R 775 /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
