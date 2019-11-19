FROM php:7.2.2-fpm
RUN apt-get update && apt-get install -y mysql-client --no-install-recommends \
 && docker-php-ext-install pdo_mysql
ENTRYPOINT php artisan migrate:fresh --seed --env=docker && php artisan serve --env=docker --host=0.0.0.0 --port=8000

