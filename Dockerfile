FROM dhi.io/node:22-debian13-dev AS node-builder
RUN mkdir -p /app && cp -r "$(npm prefix -g)" node


FROM dhi.io/php:8.4-debian13-dev AS base
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libwebp-dev \
        libzip-dev \
        libpcre2-dev \
        zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*


FROM base AS php-ext-builder
RUN cd "$PHP_SRC_DIR/ext/gd"  \
    && phpize \
    && ./configure \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && make -j$(nproc) \
    && make install
RUN pecl install zip

RUN mkdir -p /usr/local/lib/php && cp -r "$(php-config --extension-dir)" /usr/local/lib/php/extensions
RUN echo "extension=gd" > $PHP_INI_DIR/conf.d/gd.ini
RUN echo "extension=zip" > $PHP_INI_DIR/conf.d/zip.ini

RUN cp $PHP_INI_DIR/php.ini $PHP_INI_DIR/php.ini-development

RUN cp $PHP_INI_DIR/php.ini $PHP_INI_DIR/php.ini-production
RUN echo "extension_dir=/usr/local/lib/php/extensions" >> $PHP_INI_DIR/php.ini-production


FROM php-ext-builder AS php-runtime-deps

# DHI runtime images do not include apt, so copy the actual shared-library
# closure required by the compiled extensions into the final image.
RUN mkdir -p /runtime-root \
    && ldd /usr/local/lib/php/extensions/gd.so /usr/local/lib/php/extensions/zip.so \
        | awk '($2 == "=>" && $3 ~ /^\//) { print $3 } ($1 ~ /^\// && $1 !~ /:$/) { print $1 }' \
        | sort -u \
        | while read -r path; do \
            real_path="$(readlink -f "$path")"; \
            mkdir -p "/runtime-root$(dirname "$real_path")"; \
            cp -a "$real_path" "/runtime-root$real_path"; \
            if [ "$(basename "$path")" != "$(basename "$real_path")" ]; then \
                ln -sf "$(basename "$real_path")" "/runtime-root$(dirname "$real_path")/$(basename "$path")"; \
            fi; \
        done



FROM dhi.io/composer:2.2-debian13-php8.4-dev AS composer-builder


FROM php-ext-builder AS php-builder
COPY --from=composer-builder /usr/local/bin/composer /usr/local/bin/composer
RUN apt-get update && apt-get install -y --no-install-recommends \
    zip  # required for Composer


COPY composer.json composer.lock /app/
WORKDIR /app
RUN ls -la
RUN chown -R nonroot:nonroot /app

USER nonroot
RUN composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress


FROM dhi.io/php:8.4-debian13-fpm

COPY --from=php-runtime-deps /runtime-root /
COPY --from=php-ext-builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=php-ext-builder $PHP_INI_DIR/conf.d/ $PHP_INI_DIR/conf.d/
COPY --from=php-ext-builder $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY opcache.ini $PHP_INI_DIR/conf.d/opcache.ini

COPY . /var/www/
COPY --from=php-builder /app/vendor /var/www/vendor

WORKDIR /var/www/
