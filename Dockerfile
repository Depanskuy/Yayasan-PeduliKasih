FROM php:8.3-apache

# Install ekstensi PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Aktifkan Apache mod_rewrite
RUN a2enmod rewrite

# Ubah DocumentRoot Apache ke /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Izinkan .htaccess Override
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set direktori kerja
WORKDIR /var/www/html

# Salin source code proyek
COPY . /var/www/html/

# Format line endings & permissions untuk script entrypoint
RUN sed -i 's/\r$//' /var/www/html/docker-entrypoint.sh \
    && chmod +x /var/www/html/docker-entrypoint.sh

# Atur hak akses direktori uploads dan storage
RUN mkdir -p /var/www/html/public/uploads /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/public/uploads /var/www/html/storage

EXPOSE 80

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
