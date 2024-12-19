FROM ghcr.io/withercom/docker-laravel:php8.3 as builder
WORKDIR /usr/src/app
COPY . .
RUN apk add nodejs npm wget
RUN wget -O - https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/bin
RUN composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --optimize-autoloader
RUN npm install --no-package-lock && npm run build

FROM ghcr.io/withercom/docker-laravel:php8.3
COPY --chown=www-data:www-data --from=builder /usr/src/app /app
RUN apk add --no-cache wireguard-tools-wg-quick \ 
    iptables \
    jq \
    curl \
    coredns \
    openresolv