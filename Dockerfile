FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    nginx \
    supervisor \
    gettext \
    nodejs \
    npm \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    libxml2-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd bcmath pdo_mysql opcache intl zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .
COPY database.sqlite ./database.sqlite

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build
RUN php artisan config:cache
RUN php artisan storage:link || true

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/public
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY .docker/nginx.conf.template /etc/nginx/http.d/default.conf.template
COPY .docker/supervisord.conf /etc/supervisord.conf
COPY .docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
COPY .docker/fastcgi_params /etc/nginx/fastcgi_params

EXPOSE 80

CMD ["/usr/local/bin/docker-entrypoint.sh"]