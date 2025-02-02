FROM php:7.2-apache

RUN apt-get update && apt-get install zip unzip -y

RUN docker-php-ext-install mysqli

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html/

COPY composer.json ./

RUN composer install
