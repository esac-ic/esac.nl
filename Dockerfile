FROM php:7.2.2-fpm
RUN apt-get update && apt-get install -y mysql-client --no-install-recommends \
 && docker-php-ext-install pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - && apt install -y nodejs
#this next line is needed for composer install for some reason
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install zip
COPY . /var/www/
WORKDIR "/var/www/"
RUN composer install
RUN composer update
RUN npm install
RUN npm run dev
#ENTRYPOINT /bin/bash
ENTRYPOINT php artisan serve --env=docker3 --host=0.0.0.0 --port=8000

