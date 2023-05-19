FROM php:8.2-apache

RUN apt-get update
RUN apt-get upgrade -y

# Install git
RUN apt-get install -y git zip unzip zlib1g-dev

# Install redis
RUN pecl install redis
RUN docker-php-ext-enable redis

RUN pecl install xdebug

RUN docker-php-ext-enable xdebug

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Source environment variables
RUN /bin/bash -c "source /etc/apache2/envvars"

# Enable Apache rewrite module
RUN a2enmod rewrite

RUN a2enmod proxy

RUN a2enmod proxy_http

ENV PROJECT_PATH=/var/www

COPY custom.ini /usr/local/etc/php/conf.d/00-custom.ini
COPY 000-default.conf /etc/apache2/sites-enabled/000-default.conf


# Expose the port that Apache is listening on
EXPOSE 80
STOPSIGNAL SIGQUIT

CMD apache2-foreground
