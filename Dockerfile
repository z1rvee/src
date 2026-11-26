FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    pkg-config \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY index.php /var/www/html/index.php

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
