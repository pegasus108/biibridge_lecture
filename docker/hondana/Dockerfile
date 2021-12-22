FROM php:7.3-apache

COPY ./docker/hondana/000-default.conf /etc/apache2/sites-available/000-default.conf 
COPY ./hondana /var/www/sample

RUN apt-get update \
 && apt-get install -y vim \
 && apt-get install -y imagemagick libmagickwand-dev \
 && pecl install imagick \
 && docker-php-ext-enable imagick \
 && docker-php-ext-install pdo_mysql mysqli

RUN chmod -R 777 /var/www/sample 
WORKDIR /var/www/sample