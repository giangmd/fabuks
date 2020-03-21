# Endpoint mock
Fabuks example awesome!!!

## Required

 - Docker Desktop
 - Git
 - Composer
 - PHP >= 7.3
 - Mariadb >= 10.4
 - Node
 - Npm

## Setup

- git clone https://github.com/giangmd/fabuks.git fabuks
- cd fabuks

**=> Build docker**

`docker-compose up -d --build`

**=> Config Laravel project**

- cd src
- composer install --no-scripts
- cp .env.example .env
- docker exec fabuks_app php artisan key:generate
- docker exec fabuks_app php artisan migrate
- docker exec fabuks_app php artisan passport:install

**=> Creating A Password Grant Client For API**

`docker exec fabuks_app php artisan passport:client --password`

Config API_CLIENT_SECRET and API_CLIENT_ID in .env

**=> Build Front end Vuejs**
- cd src
- npm install
- npm run dev

**=> Copy your IP to env**

`ifconfig`

Copy IP at `en0` to `APP_URL` in .env with `8080` port from `docker-compose.yml`

**=> Run command line generate data**

`docker exec fabuks_app php artisan super:update "admin@gmail.com" "12345678" "12345678" --create`

`docker exec fabuks_app php artisan rates:generates 1.25`

## Result

- WEB: `localhost:8080`
- WEB Admin: `localhost:8080/fabuks`
- PHP MyAdmin: `localhost:8088`

## Testing
`//TODO`
