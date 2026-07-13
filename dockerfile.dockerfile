FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p storage/framework/{cache,sessions,views} \
    && chmod -R 775 storage bootstrap/cache

ENV WEBROOT=/var/www/html/public

RUN php artisan optimize:clear || true

CMD ["/start.sh"]