#!/bin/bash

docker exec $(docker ps --filter name="houses-php-fpm" -q) composer install
docker exec $(docker ps --filter name="houses-php-fpm" -q) ./artisan migrate
docker exec $(docker ps --filter name="houses-php-fpm" -q) ./artisan db:seed Houses