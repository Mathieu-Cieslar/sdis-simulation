# Étape 1 : Utiliser une image PHP officielle avec Apache
FROM php:8.2-apache

# Étape 2 : Installer les dépendances nécessaires
RUN apt-get update --fix-missing && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install intl pdo pdo_pgsql zip opcache \
    && docker-php-ext-enable opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Étape 3 : Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Étape 4 : Configurer Apache pour Symfony
RUN a2enmod rewrite

# Étape 5 : Configurer le DocumentRoot pour Symfony
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Étape 6 : Configurer le répertoire de travail
WORKDIR /var/www/html

# Étape 7 : Copier les fichiers de l'application Symfony
COPY . /var/www/html

# Étape 8 : Donner les bonnes permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Étape 9 : Installer les dépendances Symfony
RUN composer install --optimize-autoloader --no-interaction

# Étape 10 : Configurer le cache Symfony
RUN php bin/console cache:clear  \
    && php bin/console cache:warmup

# Étape 11 : Exposer le port 80
EXPOSE 80

# Étape 12 : Lancer Apache
CMD ["apache2-foreground"]
