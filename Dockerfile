FROM php:8.2-cli

# INSTALL DEPENDENCIES
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    zip

# INSTALL PDO
RUN docker-php-ext-install pdo pdo_pgsql

# INSTALL COMPOSER
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# WORKDIR
WORKDIR /app

# COPY FILES
COPY . .

# INSTALL
RUN composer install --no-dev --optimize-autoloader

# STORAGE
RUN php artisan storage:link || true

# PORT
EXPOSE 10000

# START
CMD php artisan serve --host=0.0.0.0 --port=10000