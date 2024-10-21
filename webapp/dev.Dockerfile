FROM ghcr.io/withercom/docker-laravel:php82
WORKDIR /srv/http
RUN apk add wireguard-tools-wg-quick
