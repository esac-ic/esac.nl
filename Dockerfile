# Use the specified image as the base
FROM php:8.2-fpm-bookworm

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
    nodejs \
    npm \
    && docker-php-ext-install opcache pdo_mysql zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-enable opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy files and set the working directory
COPY . /var/www/
WORKDIR "/var/www/"

# Install PHP dependencies, and NPM dependencies
RUN composer install --no-dev \
    && npm install \
    && npm run prod

# Copy opcache.ini after builds to prevent CLI opcache issues on arm64
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Configure application
RUN echo "listen = 0.0.0.0:9000" >> /usr/local/etc/php-fpm.d/www.conf \
    && php artisan storage:link \
    && php artisan config:clear \
    && cp -R public/. public_backup/ \
    && rm -rf /var/lib/apt/lists/*

# Set the entrypoint
ENTRYPOINT ["/bin/bash"]
