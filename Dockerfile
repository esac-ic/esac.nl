FROM php:8.2-fpm

# Install system dependencies and PHP extensions
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
    curl

# Configure and install PHP extensions
RUN docker-php-ext-install opcache pdo_mysql zip
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-enable opcache

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_18.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
RUN apt-get update && apt-get install -y nodejs

# Clean up APT when done
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

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
