#!/bin/sh
set -e

: "${PORT:=80}"


# Attendre que MySQL soit prêt
until php -r "try { new PDO(getenv('DB_CONNECTION') === 'mysql' ? sprintf('mysql:host=%s;port=%s;dbname=%s', getenv('DB_HOST'), getenv('DB_PORT'), getenv('DB_DATABASE')) : '', getenv('DB_USERNAME'), getenv('DB_PASSWORD')); echo 'DB OK'; } catch (Exception $e) { exit(1); }"; do
	echo "Waiting for database connection..."; sleep 3;
done

echo "==> MIGRATION START"
php artisan migrate --force
echo "==> SEED START"
php artisan db:seed --force
echo "==> SEED END"
php artisan cache:clear
php artisan config:cache
php artisan storage:link || true

exec /usr/bin/supervisord -c /etc/supervisord.conf