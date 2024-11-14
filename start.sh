#!/bin/bash

# Tworzymy katalog templates_c, jeśli nie istnieje
mkdir -p /var/www/html/public/templates_c

# Nadajemy odpowiednie uprawnienia do katalogu
chown -R www-data:www-data /var/www/html/public/templates_c
chmod -R 775 /var/www/html/public/templates_c

# Uruchamiamy serwer Apache
apache2-foreground