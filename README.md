<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
</p>

## Usage

To get started, make sure you have [Docker installed](https://docs.docker.com/docker-for-mac/install/) on your system, and then clone this repository.

Next, navigate in your terminal to the directory you cloned this, and spin up the containers for the web server by running `docker compose up -d --build app`.

**Note**: Your MySQL database host name should be `mysql`, **not** `localhost`. The username and database should both be `homestead` with a password of `secret`.

**Note**: Your Redis database host name should be `redis`, **not** `localhost`.

```dotenv
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret

REDIS_HOST=redis
REDIS_PORT=6379
```
After that completes, execute the commands in the terminal in order:
- `docker compose run --rm composer install`
- `cp .env.example .env`
- `docker compose run --rm artisan key:generate`
- `docker compose run --rm artisan jwt:secret`
- `docker compose run --rm artisan migrate`

Bringing up the Docker Compose network with `app` instead of just using `up`, ensures that only our site's containers are brought up at the start, instead of all of the command containers as well. The following are built for our web server, with their exposed ports detailed:

- **nginx** - `8080:80`
- **mysql** - `3360:3306`
- **php** - `9000:9000`
- **redis** - `6379:6379`

Two additional containers are included that handle Composer and Artisan commands *without* having to have these platforms installed on your local computer. Use the following command examples from your project root, modifying them to fit your particular use case.

- `docker compose run --rm composer update`
- `docker compose run --rm artisan migrate`

## postman documentation
https://documenter.getpostman.com/view/16995623/2s9XxsWcDD
