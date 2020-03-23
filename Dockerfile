FROM php:7.4.0-fpm
RUN apt-get update && \
   docker-php-ext-install pdo_mysql && \
   apt install -y zip \
   libzip-dev zip libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
   libfreetype6-dev zlib1g-dev  && \
   docker-php-ext-configure gd --with-freetype --with-jpeg && \
   docker-php-ext-install gd && \
   docker-php-ext-install zip && \
   rm -rf /var/lib/apt/lists/*

COPY . /var/www/
WORKDIR "/var/www/"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - && apt install -y nodejs
RUN composer config -g -- disable-tls true
RUN composer install --no-dev

#command to modify the fpm config to listen to nginx in docker-compose
# alternatief: RUN sed -e 's/127.0.0.1:9000/9000/' -i /etc/php-fpm.d/www.conf (misschien netter?)
RUN echo "listen = web:9000" >> /usr/local/etc/php-fpm.d/www.conf

RUN npm install
RUN npm run prod
RUN php artisan storage:link
RUN php artisan config:clear
RUN rm -rf /var/lib/apt/lists/*

RUN cp -R public/. public_backup/

ENTRYPOINT /bin/bash
