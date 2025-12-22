#!/bin/sh
set -e

: "${PORT:=80}"

php artisan migrate --force

exec /usr/bin/supervisord -c /etc/supervisord.conf
