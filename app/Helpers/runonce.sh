#!/bin/bash

ip_addr=$(ip addr show eth0 | awk '/inet / {print $2}' | awk -F "/" '{print $1}')
echo "$ip_addr  $DOCKER_SERVER" >> /etc/hosts

npm install
php /var/www/laravel/artisan filament:assets
npm run build

php artisan optimize:clear
php artisan icons:cache
php artisan event:cache
php artisan optimize

\cp /var/www/laravel/app/Helpers/octane.ini /etc/supervisor.d/octane.ini
\cp /var/www/laravel/app/Helpers/horizon.ini /etc/supervisor.d/horizon.ini
\cp /var/www/laravel/app/Helpers/nginx.tmpl /etc/nginx/default.tmpl

supervisorctl reload