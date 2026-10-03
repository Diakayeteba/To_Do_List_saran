# Image officielle PHP avec serveur Apache préconfiguré
FROM php:8.2-apache

# Copie de tous les fichiers du projet dans le répertoire web d'Apache
COPY . /var/www/html/

# Exposition du port web classique
EXPOSE 80