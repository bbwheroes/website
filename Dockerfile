FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libffi-dev \
    zip \
    unzip \
    nodejs \
    npm

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip ffi

# Configure FFI for turso/libsql
RUN echo "ffi.enable=true" >> /usr/local/etc/php/conf.d/docker-php-ext-ffi.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN npm install && npm run build

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

RUN a2enmod rewrite

RUN php artisan migrate --force
RUN php artisan optimize

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
