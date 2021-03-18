FROM php:8-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev --no-install-recommends \
    && docker-php-ext-install -j$(nproc) pdo_pgsql pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

CMD ["php-fpm"]
