#!/bin/sh
# Sets up the checkout on each start; every step is skipped when already done
set -e

if [ composer.lock -nt vendor/autoload.php ]; then
    composer install --no-interaction
    touch vendor/autoload.php
fi

grep -q '^APP_KEY=.' .env || php artisan key:generate
[ -L public/storage ] || php artisan storage:link

php artisan migrate --force --seed

exec docker-php-entrypoint "$@"
