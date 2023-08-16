## Commands
Migrating the database
```php
php artisan migrate
```
Starting the server instance
```php
php artisan serve
```


## Prerequisite
* PHP 7.4.33
* Composer 2.5.8

## Setup
1. Copy .env.example -> .env
2. Setup a postgres instance, if you're using docker you can run: 
```
docker run -d \
    --name betting-postgres \
    -p 5432:5432 \
    -e POSTGRES_PASSWORD=postgres \
    -e POSTGRES_DB=postgres \
    -e POSTGRES_USER=postgres \
    postgres
```
This will start a postgres instance with the username, dbname and password of "postgres"
3. To get the ip address of this database run:
```
docker inspect postgres | jq '.[0].NetworkSettings.Networks[].IPAddress' -r
```
Grab the ip address of the instance and paste it in the `.env` file under `DB_HOST`
4. If you just want login and privileges to work we suggest using our in house software for bypassing this in development, please see: [nyckeln-under-dorrmattan](https://github.com/datasektionen/nyckeln-under-dorrmattan). Install and start this project by following the provided instructions.
5. Migrate the project by running the migrate command (shown on top of this document)
6. You are now ready to start the server, run the serve command (also shown on top of this document)