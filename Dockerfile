# Use the specified image as the base
FROM php:8.4-fpm

# Install system dependencies, PHP extensions, and Node.js
RUN apt-get update && apt-get install -y \
    zip \
    libzip-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxpm-dev \
    libfreetype6-dev \
    zlib1g-dev \
    gnupg \
    ca-certificates \
    curl \
    && docker-php-ext-install opcache pdo_mysql zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-enable opcache \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_18.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list \
    && apt-get update && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Copy files and set the working directory
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY . /var/www/
WORKDIR "/var/www/"

# Install Composer, PHP dependencies, and NPM dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev \
    && npm install \
    && npm run prod

# Configure application
RUN echo "listen = web:9000" >> /usr/local/etc/php-fpm.d/www.conf \
    && php artisan storage:link \
    && php artisan config:clear \
    && cp -R public/. public_backup/ \
    && rm -rf /var/lib/apt/lists/*

# Set the entrypoint
ENTRYPOINT ["/bin/bash"]
