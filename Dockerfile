FROM node:16.17.0 as nodebase

FROM php:7.4-cli as phpbase

ARG VERSION=00000
ARG token
ENV CONSUL_TOKEN $token

EXPOSE 8080

CMD [ "/build/flualfa/start.sh", "$CONSUL_TOKEN" ]

COPY --from=nodebase /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=nodebase /usr/local/bin/node /usr/local/bin/node

RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm && \
    rm /etc/apt/preferences.d/no-debian-php && \
    apt-get update && \
    apt-get install -y wget php-cli php-zip php-gd php-pgsql unzip zlib1g-dev libzip-dev libpng-dev libpq-dev tcpdump && \
    wget https://releases.hashicorp.com/envconsul/0.13.2/envconsul_0.13.2_linux_amd64.zip && \
    unzip envconsul_0.13.2_linux_amd64.zip && \
    mv envconsul /usr/bin

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install pdo pdo_pgsql zip gd && \
    docker-php-ext-enable zip gd

WORKDIR /build/flualfa
COPY . /build/flualfa

RUN composer self-update && \
    composer update && \
    npm install && \
    composer install && \
    chmod +x /build/flualfa/start.sh
