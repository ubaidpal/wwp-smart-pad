#!/bin/bash

set -e

if [ ! -f /var/www/.env ]; then
    cp /var/www/.env.example .env
fi



if [ ! -f /var/www/storage/first-run.txt ]; then

mkdir -p /var/www/storage/framework/cache/data
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
chown -R :www-data /var/www/storage/
chmod -R 775 /var/www/storage/

composer install

php artisan migrate:refresh

php artisan db:seed

touch /var/www/storage/first-run.txt

fi








exec php-fpm

exec "$@"