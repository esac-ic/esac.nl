<p align="center"><img width="20%" src="https://i.imgur.com/M2nZitU.png"></p>
<p align="center">
<a href="https://codecov.io/gh/esac-ic/esac.nl">
  <img src="https://codecov.io/gh/esac-ic/esac.nl/branch/master/graph/badge.svg" />
</a>
</p>

## Built with
- [Laravel](https://laravel.com)
- [Vite](https://vite.dev), [Bootstrap](https://getbootstrap.com/) and [Vue](https://vuejs.org)
- [DataTables](https://datatables.net/), [Summernote](https://summernote.org/) and [Tempus Dominus](https://tempusdominus.github.io/bootstrap-4/)

## Development environment
Everything runs in Docker, using [Docker Desktop](https://www.docker.com/products/docker-desktop/) on Windows and macOS. No local PHP, Composer, Node or MySQL needed, and everyone runs the same versions as production.

On Windows, use WSL and clone into the Linux file system (e.g. `~/`), for performance.
<details>
    <summary>Why the Linux file system?</summary>

WSL can store files in two places:
 - **Linux file system**: your home directory (`~/`, `/home/<username>`). Fast, use this.
 - **Windows drive**: `/mnt/c/...`. Every file read goes through a network share, which makes the site very slow.
</details>

`compose.override.yaml` adds the dev setup on top of `compose.yaml`. Docker Compose loads it automatically; the production server only gets `compose.yaml`.

1. Clone the project repository and cd into it
	```
	git clone https://github.com/esac-ic/esac.nl.git
	cd esac.nl
	```
2. Copy `.env.example` to `.env`
	```
	cp .env.example .env
	```
3. Build and start the containers. The first run takes a few minutes: it installs the dependencies, migrates and seeds the database and creates the mailman lists. Later starts only redo what changed, e.g. after a `composer.lock` update.
	```
	./dev up
	```
4. Visit http://localhost:8000 and log in with `test@esac.nl` / `test`

Stop it with `./dev down` and start it again with `./dev up`. Outgoing mail goes to mailpit (http://localhost:8025).

PHP and Blade changes show up on the next page load, and the `assets` container rebuilds the CSS and JS on every change (`./dev logs assets`).

`./dev` is a shortcut for the common commands: `./dev art migrate`, `./dev composer require foo/bar`, `./dev npm install foo`, `./dev mysql`, `./dev logs` and `./dev bash`. Run `./dev` to see them all.

For step debugging with Xdebug, set `XDEBUG_MODE=debug` in `.env` and run `./dev up`. In your IDE, listen on port 9003 and map `/app` to the project folder.

### Running tests
Tests run against their own `esac_testing` database
```
./dev test
```

### Reseeding the database
> [!WARNING]
> This drops every table in your local database.
```
./dev art migrate:fresh --seed
```

## Production
`compose.yaml` runs the site on the server, which is set up by `infra/cloud-init.yaml`. Every push to master builds the image, backs up the database (`infra/backup.sh`), migrates and restarts the containers; see `.github/workflows/build.yml`.
