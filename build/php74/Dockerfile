FROM php:7.4-fpm

WORKDIR /var/symfony-example-command

RUN apt-get update

RUN apt-get install -y wget git libicu-dev libzip-dev unzip \
    && pecl install xdebug \
    && docker-php-ext-install zip intl opcache \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD ./php.ini /usr/local/etc/php/php.ini
