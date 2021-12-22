FROM php:8.0
WORKDIR /application
RUN apt-get update && apt-get install -y \
            git \
            zip \
            unzip \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer