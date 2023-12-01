FROM php:8.1.0-fpm as php

ENV PHP_OPCACHE_ENABLE=1
ENV PHP_OPCACHE_ENABLE_CLI=1
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=1
ENV PHP_OPCACHE_REVALIDATE_FREQ=1

RUN usermod -u 1000 www-data

RUN apt-get update -y
RUN apt-get install -y unzip git zip libpq-dev libcurl4-gnutls-dev nginx libzip-dev

# Install IBM i Access ODBC driver
RUN curl https://public.dhe.ibm.com/software/ibmi/products/odbc/debs/dists/1.1.0/ibmi-acs-1.1.0.list | tee /etc/apt/sources.list.d/ibmi-acs-1.1.0.list
RUN apt-get update
RUN apt-get install -y ibm-iaccess

RUN docker-php-ext-install pdo pdo_mysql bcmath curl opcache zip

# Install mbstring dependencies
RUN apt-get install -y libonig-dev

# Install mbstring extension
RUN docker-php-ext-install mbstring

RUN docker-php-ext-enable opcache pdo_mysql mbstring

RUN apt-get install unixodbc unixodbc-dev -y
RUN docker-php-ext-configure pdo_odbc --with-pdo-odbc=unixODBC,/usr
RUN docker-php-ext-install pdo_odbc

RUN apt update

WORKDIR /var/www

COPY --chown=www-data:www-data . .

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer

RUN chmod -R 755 /var/www/storage
RUN chmod -R 755 /var/www/bootstrap

ENTRYPOINT [ "docker/entrypoint.sh" ]
