# syntax=docker/dockerfile:1

FROM composer:2 AS composer
FROM node:24-trixie-slim AS node

FROM dunglas/frankenphp:1.13.2-php8.5.12-trixie AS base

# The MySQL client loads database/schema/mysql-schema.sql when migrating an empty database.
# MySQL's certificate is self-signed, so the client keeps TLS but doesn't verify it.
RUN apt-get update \
    && apt-get install -y --no-install-recommends libcap2-bin default-mysql-client \
    && rm -rf /var/lib/apt/lists/* \
    && printf '[client]\nssl-verify-server-cert = off\n' > /etc/mysql/mariadb.conf.d/90-esac.cnf \
    && install-php-extensions pdo_mysql gd intl zip opcache pcntl exif \
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp

COPY infra/php.ini "$PHP_INI_DIR/conf.d/zz-esac.ini"

WORKDIR /app

# Composer runs on the runtime image, so it checks the platform requirements against its PHP extensions.
FROM base AS vendor
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts

FROM node AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY . .
RUN npm run build

FROM base AS dev
COPY infra/php.dev.ini "$PHP_INI_DIR/conf.d/zzz-esac-dev.ini"
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --chmod=755 infra/dev-entrypoint.sh /usr/local/bin/dev-entrypoint

RUN install-php-extensions xdebug \
    && ln -s ../lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -s ../lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

# The container runs as your user id (compose.override.yaml), which has no home and doesn't own
# /data and /config (used by Caddy, and by tinker through XDG_CONFIG_HOME) or the node_modules volume
RUN mkdir -p /app/node_modules && chmod -R a+rwX /data /config /app/node_modules
ENV HOME=/tmp

ENTRYPOINT ["dev-entrypoint"]
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile", "--adapter", "caddyfile"]

FROM base AS production
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public ./public

RUN php artisan package:discover --ansi \
    && php artisan storage:link \
    && chown -R www-data:www-data storage bootstrap/cache /data/caddy /config/caddy

USER www-data

ENTRYPOINT ["/app/infra/entrypoint.sh"]
CMD ["--config", "/etc/frankenphp/Caddyfile", "--adapter", "caddyfile"]
