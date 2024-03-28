# Betting

System to bet when SM ends. I heard there are great rewards for those who win.

## Running
`docker compose watch`

Open http://localhost:8000/ in your browser.

Or if you hate docker, you can continue reading this document.

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
* PostgresQL

## Setup
1. Copy .env.example -> .env
2. Setup a postgres instance and database
3. Configure the database variables in .env to point to the postgres instance.
4. If you just want login and privileges to work we suggest using our in house software for bypassing this in development, please see: [nyckeln-under-dorrmattan](https://github.com/datasektionen/nyckeln-under-dorrmattan). Install and start this project by following the provided instructions.  
5. Migrate the project by running the migrate command (shown on top of this document)  
6. You are now ready to start the server, run the serve command (also shown on top of this document)  
