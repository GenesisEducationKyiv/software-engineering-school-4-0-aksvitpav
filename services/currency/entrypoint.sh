#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
  set -- php-fpm "$@"
fi

composer install
php artisan optimize:clear --no-interaction
php artisan storage:link --force --no-interaction
php artisan migrate --force  --no-interaction
php artisan db:seed --force --no-interaction
php artisan app:fetch-currency-rate
#php artisan app:get-current-rate
cp -rf /app/public/. /tmp/nginx-public/.

exec "$@"
