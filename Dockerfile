FROM php:7.4-apache


WORKDIR /var/www/html/

COPY . .

RUN a2enmod rewrite
RUN docker-php-ext-install mysqli