FROM php:7-apache
RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y openssl libssl-dev libcurl4-openssl-dev
RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN pecl install redis-4.0.1 && docker-php-ext-enable redis
