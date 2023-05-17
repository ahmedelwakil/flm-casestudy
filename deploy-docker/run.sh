#!/bin/bash

composer install
chown -R www-data:www-data /var/www/html/
chmod 777 /var/www/html/storage/ -R
php artisan migrate:install
php artisan migrate
php artisan db:seed
php artisan jwt:secret --force
apache2-foreground
