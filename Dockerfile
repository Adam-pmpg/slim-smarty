FROM php:8.2-apache

# Instalacja pakietów i rozszerzeń
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev unzip git nano curl libonig-dev

# Włącz mod_rewrite
RUN a2enmod rewrite

# Skopiowanie niestandardowej konfiguracji Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Skopiowanie pliku php.ini (jeśli masz go w swoim projekcie)
COPY config/php.ini /usr/local/etc/php/php.ini

# Instalacja rozszerzeń PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring zip

# Instalacja Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Skopiowanie aplikacji
COPY . /var/www/html/

# Ustawienie katalogu roboczego
WORKDIR /var/www/html

# Uprawnienia
RUN chown -R www-data:www-data /var/www/html

# Instalacja zależności PHP za pomocą Composer (jeśli masz plik composer.json)
RUN composer install --no-dev --optimize-autoloader

# Tworzenie brakujących katalogów (templates_c) i nadanie uprawnień
RUN mkdir -p /var/www/html/public/templates_c && \
    chown -R www-data:www-data /var/www/html/public/templates_c && \
    chmod -R 775 /var/www/html/public/templates_c

# Kopiowanie skryptu startowego do kontenera
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Zmieniamy domyślną komendę uruchamiającą kontener na nasz skrypt
CMD ["/start.sh"]

# Otwórz port
EXPOSE 80