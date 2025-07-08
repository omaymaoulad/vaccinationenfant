FROM php:8.2-cli

# Installer les extensions PHP requises
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier les fichiers Laravel
COPY . .

# Installer les dépendances Laravel
RUN composer install --optimize-autoloader --no-dev

# Droits d'accès corrects
RUN chmod -R 755 storage bootstrap/cache

# Ajouter le server.php
COPY server.php .

EXPOSE 8080

CMD ["php", "server.php"]
