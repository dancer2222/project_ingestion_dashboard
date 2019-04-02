# Ingestion Dashboard

## Local Development


Fork main repo and clone it to your local
```
git clone https://github.com/[your_github_username]/project_ingestion_dashboard dashboard
cd dashboard
composer install --ignore-platform-reqs
php artisan key:generate
```


Set proper env values in `.env` file
```
cp .env-example .env
```


We use a local database which is running on the our local server. Set the necessary values to the following variables (`.env` file)
 ```
DB_CONNECTION=ingestion
DB_HOST=
DB_PORT=
DB_DATABASE=jomedia_ingestion
DB_USERNAME=root
DB_PASSWORD=
 ```

Adapt laradock `.env` file to your needs (`dashboard/laradock/.env`)
```
cd laradock
cp .env-example .env
```

Run containers (from `laradock` folder)
```
docker-compose up -d nginx
```