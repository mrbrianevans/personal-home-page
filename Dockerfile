FROM php:7.4-apache


WORKDIR /var/www/

RUN a2enmod rewrite
RUN docker-php-ext-install mysqli

COPY www html/
COPY src src/