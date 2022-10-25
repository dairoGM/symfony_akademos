FROM php:7.4-apache-buster

USER root

WORKDIR /var/www/html

COPY . ./

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions gd curl pdo_mysql mbstring exif pcntl bcmath mysqli

RUN apt update && apt upgrade -y

RUN apt update && apt install -y \
        zip \
        curl \
        unzip 

# Get latest Composer
# COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
# RUN php /usr/local/bin/composer install

##COPY vhost.conf /etc/apache2/sites-available/000-default.conf
