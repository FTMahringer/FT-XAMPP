FROM php:8.3-apache

# Copy your custom php.ini
COPY php.ini /usr/local/etc/php/php.ini

# Install only needed packages, enable PHP extensions and Apache mods
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
       libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
       libonig-dev libxml2-dev libcurl4-openssl-dev libxslt1-dev \
       unzip git build-essential \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
       gd pdo_mysql mbstring zip opcache exif xsl soap sockets \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && a2enmod rewrite headers expires \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Simple HTTP healthcheck
HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
  CMD curl -f http://localhost/ || exit 1

EXPOSE 80
CMD ["apache2-foreground"]