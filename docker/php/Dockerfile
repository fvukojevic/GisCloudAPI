FROM php:7.3-fpm

RUN apt-get update && \
    apt-get install -y \
    gettext-base \
    bash-completion \
    nano \
    git \
    zip unzip \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    dos2unix \
    nodejs \
    npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install pdo_mysql pdo zip gd exif

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer && composer global require hirak/prestissimo --no-plugins --no-scripts

RUN sed -i -e "s/pm.max_children = 5/pm.max_children = 10/g" /usr/local/etc/php-fpm.d/www.conf

WORKDIR /app
EXPOSE 9000
