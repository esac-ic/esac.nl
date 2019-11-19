composer install
composer update
npm install
npm run dev
php artisan migrate:fresh --seed --env=docker
php artisan serve --env=docker --host=0.0.0.0 --port=8000