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
- [Vue](https://vuejs.org)
- [Tempus Dominus](https://tempusdominus.github.io/bootstrap-4/)


## Setting up steps
Create an .env file from .env.example, with your local values for 
DB\_HOST, DB\_PORT, DB\_DATABASE, DB\_USERNAME and DB\_PASSWORD
**Note**: When running your local environment on a certain port, let's say 3000, modify your `APP_URL` in the file to reflect that port, else the api's won't work

### Development environment (Ubuntu on Windows + Visual Studio Code, prevents having to install a lot on Windows)
1. Install the following:
	* Visual Studio Code: https://code.visualstudio.com/
	* Ubuntu (Windows Subsystem for Linux): https://www.microsoft.com/store/productId/9PDXGNCFSCZV (link may break, just search for Ubuntu in the Windows Store. Make sure it's from Canonical Group)
	* Enable WSL feature on Windows: Go to Open Control Panel -> Programs -> Programs and Features -> Turn Windows Feature on or off -> Check Windows Subsystem for Linux. 
2. Open Ubuntu, wait, and set a username and password when prompted
3. Install necessary packages
	```
	sudo apt-get update
	sudo apt-get upgrade
	sudo apt-get --purge remove php-common
	sudo apt-get install git php-common php-mysql php-cli php-gd php-curl php-dom php-zip composer mysql-server npm
	```
4. Start the MySQL (database) server, set a root password, create a database, and make MySQL start on root
	```
	sudo service mysql start
	sudo mysql
	ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'YOUR_PASSWORD_HERE';
	CREATE DATABASE esac;
	exit
	sudo update-rc.d mysql defaults
	```
6. Clone the project repository 
	```
	git clone https://github.com/esac-ic/esac.nl.git
	```
7. Move into the repository root 
	```
	cd esac.nl/
	```
8. Copy .env.example to .env
	```
	cp .env.example .env
	```
9. Edit .env using nano
	```
	nano .env
	```
   Alternatively, you can open the repository in VScode and use the IDE to edit the .env file
	```
 	code .
  	```
10. As of now, the only thing you need to change is DB_PASSWORD. If you mindlessly copied the commands in step 4, the DB_PASSWORD should be set to "YOUR_PASSWORD_HERE". If you're using nano, press Ctrl X and follow instructions. If you're using vscode, just save the file.
11. Make sure you've enabled the following extensions in your php.ini file. To find the location, use `php --ini` and note down the "Loaded Configuration File" path. You can use `sudo nano [path]` to then edit it. Make sure that these lines are uncommented (use Ctrl+W to search if you wish):
	```
	extension=pdo_mysql
	```
12. Execute the following commands from the repository root
	```
	composer install
	php artisan migrate
	php artisan db:seed
	php artisan key:generate
	php artisan storage:link
	npm install
	npm run dev
	```
	When you make changes to CSS or JS file you need to build again which can be done with
	```
	npm run dev
	```
	It is also possible to recompile your files when you make a change by running the following command
	```
	npm run watch
	```
13. Open another Ubuntu (from Windows Start) and run, from the repository root
	```
	code .
	```
	This will open Visual Studio Code in the codebase.
14. Then run
	```
	php artisan serve
	```
15. You can then go to localhost:8000 in your favorite browser and view your own version of the esac website!

If you get errors that you cannot access the database or that the database is down, you can start mysql using `sudo service mysql start`. If you want you can check if mysql is running with `sudo service mysql status`.

#### Starting up the development environment again later
1. Open ubuntu
2. Navigate to the repository root using `cd esac.nl`
4. Run either `npm run dev` or `npm run watch` to start the javascript development server
6. Open a new terminal instance and run `code .` from the repository root to open VScode
7. Then run `php artisan serve` to start up the laravel development server.
8. Go to localhost:8000 in your favorite browser to view your own version of the ESAC website

#### Reseeding the database
If you want to reseed the database because, for example, you want to regenerate the agenda items, run the following commands:

**WARNING:** don't use the first command if there is any important data in your database that isn't backed up.
```
php artisan migrate:fresh
php artisan db:seed
```
The first command will drop all your tables and rerun all migrations and the second command will seed the database with data again. 

### Development environment (Windows)
1. you need the following programs to run the code on your local environment:
	* A web serverXampp: https://www.apachefriends.org/index.html
	* Composer: https://getcomposer.org/download/
	* NodeJS: https://nodejs.org/en/download/
	* Git: https://git-scm.com/download/win

2. Clone the project repository
	```
	git clone https://github.com/esac-ic/esac.nl.git
	```
3. Create an .env file from .env.example, with your local values for 
DB\_HOST, DB\_PORT, DB\_DATABASE, DB\_USERNAME and DB\_PASSWORD
**Note**: When running your local environment on a certain port, let's say 3000, modify your `APP_URL` in the file to reflect that port, else the api's won't work

4. Make sure you've enabled the following extensions in your php.ini file, this can be found next to the php.exe location
	```
	extension=fileinfo
	extension=gd
	extension=pdo_mysql
	```
5. Create the Esac database within mysql by launching the Admin portal in Xampp and creating the database 'esac' manually.
6. Execute the following commands from the repository root
	```
	composer install
	php artisan migrate
	php artisan db:seed
	php artisan key:generate
	php artisan storage:link
	npm install
	npm run dev
	```
	When you make changes to CSS or JS file you need to build again which can be done with
	```
	npm run dev
	```
	It is also possible to recompile your files when you make a change by running the following command
	```
	npm run watch
	```
7. Run the dev server locally
	```
    cd public
    php -S localhost:8000
	```

### Production environment
When you update the server make sure you run the following commands
```
php artisan migrate
npm run production
```
