#!/usr/bin/env sh

set -e

env=${APP_ENV:-production}
tickSleep=${COMMAND_SLEEP:-60}

cd /app

if [ "$env" != "local" ]; then
    echo "Caching..."
    (php artisan config:cache && php artisan route:cache)
fi

if [ "$1" = "app" ]; then
    echo "Artisan Execution..."
    php artisan vendor:publish --tag=log-viewer-assets --force
    #php artisan l5-swagger:generate
    /usr/bin/supervisord -c /etc/supervisord.conf
elif [ "$1" = "scheduler" ]; then
    while true
    do
      php /app/artisan schedule:run --verbose --no-interaction
      sleep "$tickSleep"
    done
elif [ "$1" = "artisan" ]; then
    while true
    do
      php "$@"
      if [ "$tickSleep" -gt 0 ]; then
        sleep "$tickSleep"
      else
        break
      fi
    done
else
    echo "Could not match any case for given args: " "$@"
fi

exit 1
