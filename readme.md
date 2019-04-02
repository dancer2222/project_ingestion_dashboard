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


Enter to workspace
```
cd laradock
docker-compose exec workspace bash
// do smth
```

Default user: username

Frontend (run from root folder).

Source files are in: `resources/scss`, `resources/js`, `resources/css` and so on.
```
npm run watch-poll 
```
___
- Laravel [docs](https://laravel.com/docs/5.8/)
- Stuck in roles/permissions? Read [ spatie docs ](https://github.com/spatie/laravel-permission)
- Laradock [ guides ](https://laradock.io/guides/)
- Template [demo](https://colorlib.com/polygon/elaadmin/index.html) and [source](https://github.com/puikinsh/ElaAdmin/tree/master)