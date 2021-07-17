FROM php:7.4-apache

RUN apt -y update
RUN apt -y upgrade
RUN apt -y install apt-utils

RUN apt-get -y update
RUN apt-get -y install git unzip vim zip
RUN apt-get -y install libcurl4-openssl-dev pkg-config
RUN apt-get -y install curl
RUN apt-get -y install libonig-dev
RUN apt-get -y install libpq-dev
RUN apt-get -y install iputils-ping

# https://github.com/dbcli/mycli
RUN apt-get -y install mycli

RUN docker-php-ext-install curl
RUN docker-php-ext-install json
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install pgsql

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN a2enmod rewrite && service apache2 restart
