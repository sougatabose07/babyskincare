FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p storage/framework/{cache,sessions,views} \
    && chmod -R 775 storage bootstrap/cache

ENV WEBROOT=/var/www/html/public

RUN php artisan route:clear || true
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true
RUN php artisan migrate --force || true

CMD ["/start.sh"]