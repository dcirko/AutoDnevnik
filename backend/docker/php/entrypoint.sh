#!/bin/sh
set -eu

install -d -o www-data -g www-data -m 0770 \
    /var/www/html/bootstrap/cache \
    /var/www/html/storage \
    /var/www/html/storage/framework \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs

exec docker-php-entrypoint "$@"
