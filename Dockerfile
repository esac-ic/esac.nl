FROM php:8.2-fpm

# Node.js major version
NODE_MAJOR=18

# Install system dependencies, PHP extensions and Node.js
RUN apt-get update && \
    docker-php-ext-install opcache && \
    docker-php-ext-enable opcache && \
    docker-php-ext-install pdo_mysql && \
    apt-get install -y zip \
    libzip-dev zip libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6-dev zlib1g-dev gnupg ca-certificates curl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    docker-php-ext-install zip && \
    mkdir -p /etc/apt/keyrings && \
    curl -fsSL https://deb.nodesource.com/gpgkey/nodesource.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg && \
    echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list && \
    apt-get update && \
    apt-get install nodejs -y && \
    rm -rf /var/lib/apt/lists/*

# Copy files and set the working directory
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY . /var/www/
WORKDIR "/var/www/"

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Disable TLS for Composer and install PHP dependencies
RUN composer config disable-tls true
RUN composer install --no-dev

# Update the FPM configuration to listen on the port expected by nginx in docker-compose
RUN echo "listen = web:9000" >> /usr/local/etc/php-fpm.d/www.conf

# Install NPM dependencies, build assets, link storage and clear configuration cache
RUN npm install
RUN npm run prod
RUN php artisan storage:link
RUN php artisan config:clear

# Backup the public directory
RUN cp -R public/. public_backup/

# Clear APT cache
RUN rm -rf /var/lib/apt/lists/*

# Set the entrypoint
ENTRYPOINT ["/bin/bash"]
