FROM php:apache

COPY ./src /var/www/html
COPY ./db /var/www/db

EXPOSE 80