#!/bin/bash

docker exec $(docker ps --filter name="houses-php-fpm" -q) composer install
docker exec $(docker ps --filter name="houses-php-fpm" -q) ./artisan migrate --force
docker exec $(docker ps --filter name="houses-php-fpm" -q) ./artisan db:seed Houses --force