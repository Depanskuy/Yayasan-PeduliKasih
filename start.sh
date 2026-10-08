#!/bin/bash
# start.sh - Railway startup script

# Copy apache config
cp /var/www/html/apache.conf /etc/apache2/sites-available/000-default.conf

# Enable mod_rewrite
a2enmod rewrite

# Create uploads directory if not exists
mkdir -p /var/www/html/public/uploads
chmod -R 777 /var/www/html/public/uploads

# Create storage/logs directory
mkdir -p /var/www/html/storage/logs
chmod -R 777 /var/www/html/storage

# Start Apache
apache2-foreground
