FROM php:7.4.0-fpm
RUN apt-get update && \
   docker-php-ext-install pdo_mysql && \
   apt install -y zip \
   libzip-dev zip libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
   libfreetype6-dev zlib1g-dev  && \
   docker-php-ext-configure gd --with-freetype --with-jpeg && \
   docker-php-ext-install gd && \
   apt-get install -y git && \
   rm -rf /var/lib/apt/lists/*

COPY . /var/www/
WORKDIR "/var/www/"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sL https://deb.nodesource.com/setup_13.x | bash - && apt install -y nodejs

RUN composer install --no-dev


#command to modify the fpm config to listen to nginx in docker-compose
# alternatief: RUN sed -e 's/127.0.0.1:9000/9000/' -i /etc/php-fpm.d/www.conf (misschien netter?)
RUN echo "listen = web:9000" >> /usr/local/etc/php-fpm.d/www.conf

#command to fix the export excel bug, cannot get this fixed in another way:
RUN sed -i '288s/continue/continue 2/' /var/www/vendor/phpoffice/phpexcel/Classes/PHPExcel/Shared/OLE.php

RUN npm install
RUN npm run prod
RUN php artisan storage:link
RUN php artisan config:clear
RUN rm -rf /var/lib/apt/lists/*

ENTRYPOINT /bin/bash