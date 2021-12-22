FROM php:8.0-apache

COPY ./docker/laravel/php.ini /usr/local/etc/php/
COPY ./docker/laravel/000-default.conf /etc/apache2/sites-available/000-default.conf 
COPY ./laravel /var/www/html

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
            zip \
            unzip \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && docker-php-ext-install pdo_mysql \
    && composer install --prefer-dist \
    && a2enmod rewrite

RUN php artisan cache:clear \
    && php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear 

RUN chmod -R 777 /var/www/html 