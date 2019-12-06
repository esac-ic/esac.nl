FROM php:7.2.25-fpm
RUN apt-get update && docker-php-ext-install pdo_mysql && apt install -y zip \
   libzip-dev zip libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
   libfreetype6-dev zlib1g-dev  && docker-php-ext-configure gd --with-gd --with-webp-dir --with-jpeg-dir \
   --with-png-dir --with-zlib-dir --with-xpm-dir --with-freetype-dir \
   --enable-gd-native-ttf && docker-php-ext-install gd
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - && apt install -y nodejs
#this next line is needed for composer install for some reason

COPY . /var/www/
WORKDIR "/var/www/"

#installing zip for composer to work
#RUN apt install -y zip libzip-dev zip

RUN composer install --no-dev

#RUN apt-get install -y \
#        libzip-dev \
#        zip \
#  && docker-php-ext-configure zip --with-libzip \
#  && docker-php-ext-install zip

#RUN docker-php-ext-install mysqli pdo pdo_mysql
#RUN apt-get update -y && apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
#    libfreetype6-dev
#RUN apt-get update && \
#    apt-get install -y \
#        zlib1g-dev


#RUN docker-php-ext-install mbstring
#
#RUN apt-get install -y libzip-dev
#RUN docker-php-ext-install zip

#RUN docker-php-ext-configure gd --with-gd --with-webp-dir --with-jpeg-dir \
#    --with-png-dir --with-zlib-dir --with-xpm-dir --with-freetype-dir \
#    --enable-gd-native-ttf
#
#RUN docker-php-ext-install gd


#RUN chown -R www-data:www-data \
#        /var/www/storage

#command to modify the fpm config to listen to nginx in docker-compose
RUN echo "listen = web:9000" >> /usr/local/etc/php-fpm.d/www.conf
# alternatief: RUN sed -e 's/127.0.0.1:9000/9000/' -i /etc/php-fpm.d/www.conf (misschien netter?)
RUN composer install --no-dev
RUN npm install
RUN npm run dev
RUN php artisan storage:link
RUN php artisan config:clear


ENTRYPOINT /bin/bash




#TODO: remove mariadb clients, clean up whole dockerfile making it smaller, remove everything which is not needed
#TODO: remove intallation files to make the image smaller
#TODO: logs omzetten in stdout ipv naar files te loggen