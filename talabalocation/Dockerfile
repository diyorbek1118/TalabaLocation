FROM php:8.2-fpm

# Sistem paketlarini o'rnatish va PHP kengaytmalarini qo'shish
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring bcmath zip gd

# Composer o'rnatish
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

