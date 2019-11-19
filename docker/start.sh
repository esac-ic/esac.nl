composer install
composer update
npm install
npm run dev
php artisan migrate:fresh --seed --env=docker
php artisan serve --env=docker --host=0.0.0.0 --port=8000
echo "als je hierboven geen foutmeldingen ziet kun je nu de esac test website vinden op http://127.0.0.1 veel plezier!"