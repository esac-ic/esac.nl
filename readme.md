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
	If you get issues with npm, you can install [nvm](https://github.com/nvm-sh/nvm) and run `nvm install --lts` to install a version of node that should work.
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


### Starting up the development environment again later
1. Open ubuntu
2. Navigate to the repository root using `cd esac.nl`
4. Run either `npm run dev` or `npm run watch` to start the javascript development server
6. Open a new terminal instance and run `code .` from the repository root to open VScode
7. Then run `php artisan serve` to start up the laravel development server.
8. Go to localhost:8000 in your favorite browser to view your own version of the ESAC website

If you get errors that you cannot access the database or that the database is down, you can start mysql using `sudo service mysql start`. If you want to, you can check if mysql is running with `sudo service mysql status`.

### Reseeding the database
If you want to reseed the database because, for example, you want to regenerate the agenda items, run the following commands:

>**WARNING:** don't use the first command if there is any important data in your database that isn't backed up.
```
php artisan migrate:fresh
php artisan db:seed
```
The first command will drop all your tables and rerun all migrations and the second command will seed the database with data again. 

### Installing mailman locally

This guide is based on https://docs.mailman3.org/en/latest/install/virtualenv.html but adapted to using mysql. 
The guide also assumes you're using wsl.

1. Run `sudo apt install python3-dev python3-venv sassc lynx` to install the dependencies

2. Log in to mysql using `mysql -u root -p` and fill in the password of your root mysql user (should be in your .env).

3. Run the following SQL commands:
```
CREATE DATABASE mailman;
CREATE USER 'mailman'@'localhost' IDENTIFIED BY 'mailman';
GRANT ALL PRIVILEGES ON mailman.* TO 'mailman'@'localhost' WITH GRANT OPTION;
exit
```
This creates a mailman database and mysql user with username and password `mailman` that has all rights for that database.

4. Set up a mailman user by running the following commands from a terminal with root access (the terminal you get when you open wsl should work)

```
sudo useradd -m -d /opt/mailman -s /usr/bin/bash mailman
sudo chown mailman:mailman /opt/mailman
sudo chmod 755 /opt/mailman
```
>You can give your mailman user a password by running `sudo passwrd mailman`

4. Switch to the mailman user by running `su mailman`

5. Set up and start a venv by running the following commands FROM YOUR MAILMAN USER
```
cd ~
python3 -m venv venv
source /opt/mailman/venv/bin/activate
echo 'source /opt/mailman/venv/bin/activate' >> /opt/mailman/.bashrc
```
> Now your terminal should look like `(venv) something@something:~$`

The last command makes it so that the venv is started automatically when you switch to your mailman user.

6. Install mailman core and some dependencies by running
```
pip install wheel mailman PyMySQL cryptography
```

7. Switch to your admin user by either opening a new terminal or running `su USERNAME`

8. Make and open the mailman config file by running the following commands:
```
cd /etc
mkdir mailman3
nano /etc/mailman3/mailman.cfg
```

9. Copy the following text into the config file and then save the file:
```
# /etc/mailman3/mailman.cfg
[paths.here]
var_dir: /opt/mailman/mm/var

[mailman]
layout: here
# This address is the "site owner" address.  Certain messages which must be
# delivered to a human, but which can't be delivered to a list owner (e.g. a
# bounce from a list owner), will be sent to this address.  It should point to
# a human.
site_owner: YOUR_OWN_EMAIL_ADRESS

[database]
class: mailman.database.MySQLDatabase
url: mysql+pymysql://mailman:mailman@localhost/mailman?charset=utf8mb4&use_unicode=1 

[archiver.prototype]
enable: yes

# For the HyperKitty archiver.
[archiver.hyperkitty]
class: mailman_hyperkitty.Archiver
enable: yes
configuration: /etc/mailman3/mailman-hyperkitty.cfg

[shell]
history_file: $var_dir/history.py

[mta]
verp_confirmations: yes
verp_personalized_deliveries: yes
verp_delivery_interval: 1
```
> Make sure you change `site_owner` to your own email address. You shouldn't receive any emails here, but better safe than sorry.

10. Run `nano /etc/mailman3/mailman-hyperkitty.cfg` and paste the following text in the file and then save:

```
[general]
base_url: http://127.0.0.1:8000/archives/
api_key: Secret_Hyperkitty_API_Key
```

11. From your admin/superuser run to install postfix
```
sudo apt install postfix
```
12. Choose "Internet site" when prompted during installation

13. Next you should be prompted for your system mail name. 

>If you have a tue laptop the default hostname should look something like "S12345678.campus.tue.nl" and you don't need to change anything.
>Elsewise you need to make sure the hostname looks like "something.something". This way you won't actually be able to receive emails to the lists, but this shouldn't be an issue for local testing.

14. Run `nano /etc/postfix/main.cf` and add the following lines to the bottom of the file
```

unknown_local_recipient_reject_code = 550
owner_request_special = no

transport_maps =
    hash:/opt/mailman/mm/var/data/postfix_lmtp
local_recipient_maps =
    hash:/opt/mailman/mm/var/data/postfix_lmtp
relay_domains =
    hash:/opt/mailman/mm/var/data/postfix_domains
```

15. Switch to your mailman user (`su mailman`) and run `mailman info` to check if everything works correctly. As of writing, your output should look like:
```
GNU Mailman 3.3.10(Tom Sawyer)
Python 3.10.12 (main, Nov 20 2023, 15:14:05) [GCC 11.4.0]
config file: /etc/mailman3/mailman.cfg
db url: mysql+pymysql://mailman:mailman@localhost/mailman?charset=utf8mb4&use_unicode=1
devmode: DISABLED
REST root url: http://localhost:8001/3.1/
REST credentials: restadmin:restpass 
```

16. Add some essential maillists by running the following commands:
```
mailman create alle-leden@esac.nl
mailman create lid@esac.nl
mailman create nieuwsbrief@esac.nl
mailman create reunist@esac.nl
mailman create pending@esac.nl
```

17. You can now run mailman by running `mailman start` from the "mailman" user.

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
5. Execute the following commands from the repository root
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

### Production environment
When you update the server make sure you run the following commands
```
php artisan migrate
npm run production
```
