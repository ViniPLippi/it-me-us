#!/bin/bash

trap  "echo TRAPed signal" HUP INT QUIT TERM

shopt -s extglob
cp -r /build/flualfa/* /dist/flualfa
cp -r /build/flualfa/.!(@(|.)) /dist/flualfa

cd /dist/flualfa

echo "Versão: $VERSION"
sed -i 's/APP_BUILD=00000/APP_BUILD='$VERSION'/g' .env

php artisan key:generate
php artisan migrate
npm run dev
php artisan route:clear
php artisan storage:link
nohup php artisan queue:work --daemon &

#envconsul -consul-addr=consul:8500 -consul-token=$CONSUL_TOKEN -prefix=labqs/flualfa php artisan serve --port=8080

echo "exited $0"
