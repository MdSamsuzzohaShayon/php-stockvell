#!/bin/bash
set -e

echo "Waiting for MySQL..."

until mysql -h db -u shayon -pTest1234 -e "SELECT 1" stockvel_db >/dev/null 2>&1; do
  echo "MySQL not ready yet..."
  sleep 3
done

echo "MySQL is ready!"

echo "Checking vendor..."
if [ ! -d "/var/www/html/vendor" ]; then
  composer install --no-interaction --no-dev --optimize-autoloader --ignore-platform-reqs
fi

echo "Running migrations..."
php /var/www/html/database-migrations.php

echo "Starting Apache..."
exec apache2-foreground