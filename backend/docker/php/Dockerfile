FROM php:8.4-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/autodnevnik.ini
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/entrypoint.sh /usr/local/bin/autodnevnik-entrypoint
RUN chmod +x /usr/local/bin/autodnevnik-entrypoint

WORKDIR /var/www/html

ENTRYPOINT ["autodnevnik-entrypoint"]
CMD ["apache2-foreground"]
