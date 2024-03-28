#!/bin/bash

trap  "echo TRAPed signal" HUP INT QUIT TERM

php artisan key:generate
php artisan migrate
npm run dev
php artisan route:clear
php artisan storage:link
nohup php artisan queue:work --daemon &

#envconsul -consul-addr=consul:8500 -consul-token=$CONSUL_TOKEN -prefix=labqs/flualfa php artisan serve --port=8080

echo "exited $0"
