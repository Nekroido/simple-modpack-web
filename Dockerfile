FROM php:7.3-apache

VOLUME [ "/var/www/html/mods" ]

EXPOSE 80 443

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN apt-get update \
    && apt-get install -y \
    libzip-dev zlib1g-dev \
    && docker-php-ext-install zip

# Enable debug stacktrace
RUN echo "LogLevel debug" >> apache2.conf

# Enable mod_rewrite
RUN a2enmod rewrite

COPY src/ /var/www/html/

RUN mkdir -p /tmp/simple-modpack && chown www-data:www-data /tmp/simple-modpack && chmod a+rwx -R /tmp/simple-modpack
