FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

CMD ["/start.sh"]