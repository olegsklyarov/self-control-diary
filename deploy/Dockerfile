FROM php:8-fpm

RUN apt-get update && apt-get install -y \
    curl \
    wget \
    git \
    unzip \
    libzip-dev \
    libpq-dev --no-install-recommends \
    && docker-php-ext-install -j$(nproc) pdo_pgsql pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

CMD ["php-fpm"]
