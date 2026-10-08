#!/bin/bash
set -e

# Buat folder uploads dan storage jika belum ada
mkdir -p /var/www/html/public/uploads /var/www/html/storage/logs
chmod -R 777 /var/www/html/public/uploads /var/www/html/storage 2>/dev/null || true

# Jalankan auto-init database jika MySQL siap
php /var/www/html/bin/init-db.php || true

# Sesuaikan port Apache jika ada env PORT (Railway memberikan $PORT)
if [ -n "$PORT" ]; then
    echo "Configuring Apache to listen on port $PORT"
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:$PORT>/g" /etc/apache2/sites-available/*.conf
fi

exec "$@"
