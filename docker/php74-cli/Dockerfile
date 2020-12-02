FROM php:7.4-cli

MAINTAINER Aleksandr Kravchuk <aleksandr.kravchuk.os@gmail.com>

RUN apt-get update \
    && apt-get install -y curl libfreetype6-dev libmcrypt-dev libzip-dev git libonig-dev \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install iconv zip opcache \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

RUN pecl install -o -f redis  \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

WORKDIR /code

CMD ["tail", "-f", "/dev/null"]