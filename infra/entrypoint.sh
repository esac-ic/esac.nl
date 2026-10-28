#!/bin/sh
set -e

# Cache the config, routes, events and views at start, so every service (app, worker, scheduler) runs on them.
php artisan optimize --no-interaction > /dev/null

exec docker-php-entrypoint "$@"
