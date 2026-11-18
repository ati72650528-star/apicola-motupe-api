# 1. Imagen base oficial: PHP 8.3 sobre servidor Apache
FROM php:8.3-apache

# 2. Habilita el módulo mod_rewrite de Apache (imprescindible para el enrutamiento de Laravel)
RUN a2enmod rewrite

# 3. Instala librerías del sistema Linux y la extensión pdo_mysql para la base de datos
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip

# 4. Inyecta el ejecutable binario de Composer desde su imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Apache apunta a la carpeta public de Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/apicola-api/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf