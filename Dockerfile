FROM php:8.2-cli

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier tous les fichiers dans le conteneur
COPY . .

# Installer les dépendances Laravel
RUN composer install --optimize-autoloader --no-dev

# Donner les bons droits d'accès
RUN chmod -R 755 storage bootstrap/cache

# Exposer le port
EXPOSE 8080

# ✅ Commande de démarrage sans migration automatique
CMD php artisan config:cache && php -S 0.0.0.0:8080 -t public server.php
