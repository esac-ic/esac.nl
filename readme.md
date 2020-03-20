<p align="center"><img width="20%" src="https://i.imgur.com/M2nZitU.png"></p>
<p align="center">
<a href="https://travis-ci.org/esac-ic/esac.nl"><img src="https://travis-ci.org/esac-ic/esac.nl.svg?branch=master" alt="Build Status"></a>
<a href="https://codecov.io/gh/esac-ic/esac.nl">
  <img src="https://codecov.io/gh/esac-ic/esac.nl/branch/master/graph/badge.svg" />
</a>
</p>

## Framework/libaries

The following libaries and framework are used:
- [Laravel](https://laravel.com)
- [DataTables](https://datatables.net/)
- [Laravel HTML Form](https://github.com/LaravelCollective/html)
- [Summernote](http://summernote.org/)
- [Laravel-Mix (Webpack)](https://github.com/JeffreyWay/laravel-mix)
- [Bootstrap](https://getbootstrap.com/)
- [PhotoSwipe](https://photoswipe.com/)
- [Vue](https://vuejs.org)
- [Tempus Dominus](https://tempusdominus.github.io/bootstrap-4/)


## Setting up steps
Create an .env file from .env.example, with your local values for 
DB\_HOST, DB\_PORT, DB\_DATABASE, DB\_USERNAME and DB\_PASSWORD
**Note**: When running your local environment on a certain port, let's say 3000, modify your `APP_URL` in the file to reflect that port, else the api's won't work

### Development environment (Windows)
you need the following programs to run the code on your local environment:
* A web serverXampp: https://www.apachefriends.org/index.html
* Composer: https://getcomposer.org/download/
* NodeJS: https://nodejs.org/en/download/
* Git: https://git-scm.com/download/win

Clone the project repository

    git clone https://github.com/esac-ic/esac.nl.git

Execute the following commands from the repository root

	composer install
    php artisan migrate
    php artisan db:seed
    php artisan key:generate
    php artisan storage:link
    npm install
    npm run dev

When you make changes to CSS or JS file you need to build again which can be done with

    npm run dev

It is also possible to recompile your files when you make a change by running the following command

    npm run watch

### Production environment
When you update the server make sure you run the following commands

    php artisan migrate
    npm run production
