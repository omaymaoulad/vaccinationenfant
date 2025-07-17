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

# Exposer le port requis par Render (⚠️ 10000 obligatoire)
EXPOSE 10000

# ✅ Lancer Laravel avec le serveur Artisan sur le bon port
CMD php artisan config:cache && php artisan serve --host=0.0.0.0 --port=10000
