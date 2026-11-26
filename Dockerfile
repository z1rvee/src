# ============================================================================
# DARKIOS Admin Panel — PHP + Apache
# ============================================================================
FROM php:8.3-apache

# Sistem paketləri
RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev \
        unzip \
        curl \
        ca-certificates \
    && docker-php-ext-install zip opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Apache modulları
RUN a2enmod rewrite headers expires

# PHP konfiqurasiyası
COPY php.ini /usr/local/etc/php/conf.d/darkios.ini

# Uygulama faylları
COPY app/ /var/www/html/

# Data qovluğu (keys.json üçün) — yazma icazəsi
RUN mkdir -p /var/www/html/darkios_data \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/darkios_data

# Sağlamlıq yoxlaması
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -fsS http://localhost/ || exit 1

EXPOSE 80

# Default entrypoint (Apache foreground)
CMD ["apache2-foreground"]
