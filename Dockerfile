FROM php:7.3.3-apache

RUN apt update && apt install -y git \
                                 zip \
                                 gettext \
                                 libxml2-dev \
                                 libc-client-dev \
                                 libkrb5-dev \
                                 openssl \
                                 netcat \
                                 gcc make autoconf libc-dev pkg-config

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install mailparse \
    && docker-php-ext-enable mailparse
# imap
RUN apt-get update && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install gd
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
        && docker-php-ext-install pdo pdo_mysql soap mbstring tokenizer xml imap

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install zip



# replace shell with bash so we can source files
RUN rm /bin/sh && ln -s /bin/bash /bin/sh

# update the repository sources list
# and install dependencies
RUN apt-get update \
    && apt-get install -y curl \
    && apt-get -y autoclean

# nvm environment variables
ENV NVM_DIR /usr/local/nvm
ENV NODE_VERSION 10.15.3

# install nvm
# https://github.com/creationix/nvm#install-script
RUN curl --silent -o- https://raw.githubusercontent.com/creationix/nvm/v0.31.2/install.sh | bash

# install node and npm
RUN source $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default

# add node and npm to path so the commands are available
ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

# copy code
COPY . /var/www/html/
COPY ./docker/deps/apache2/000-default.conf /etc/apache2/sites-available/

Run composer
RUN composer install --optimize-autoloader
 
# build js
#WORKDIR /var/www/html/resources/assets/js/desktop
#RUN npm install --only=production
#RUN npm run build
#RUN rm -rf ./node_modules

# Enable mod_rewrite to enable URL matching in apache
RUN a2enmod rewrite
 
WORKDIR /var/www/html/
 
RUN chown www-data:www-data -R ./
 
RUN php artisan clear-compiled
RUN composer dump-autoload
RUN php artisan optimize
 
 RUN php artisan route:clear
 
EXPOSE 80