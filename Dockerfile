# syntax=docker/dockerfile:1

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts

FROM node:24-trixie-slim AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY . .
RUN npm run production

FROM dunglas/frankenphp:1.13.2-php8.5.12-trixie

# The MySQL client loads database/schema/mysql-schema.sql when migrating an empty database.
# MySQL's certificate is self-signed, so the client keeps TLS but doesn't verify it.
RUN apt-get update \
    && apt-get install -y --no-install-recommends libcap2-bin default-mysql-client \
    && rm -rf /var/lib/apt/lists/* \
    && printf '[client]\nssl-verify-server-cert = off\n' > /etc/mysql/mariadb.conf.d/90-esac.cnf \
    && install-php-extensions pdo_mysql gd zip opcache \
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp

COPY infra/php.ini "$PHP_INI_DIR/conf.d/zz-esac.ini"

WORKDIR /app
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public ./public

RUN php artisan package:discover --ansi \
    && php artisan storage:link \
    && chown -R www-data:www-data storage bootstrap/cache /data/caddy /config/caddy

USER www-data
