FROM serversideup/php:8.3-fpm-nginx

USER root

WORKDIR /var/www/html

RUN install-php-extensions pcntl redis gd bcmath exif

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 80

CMD ["bash", "-lc", "php -v && php artisan config:cache && php artisan route:cache || true && php-fpm-healthcheck & start-container"]

