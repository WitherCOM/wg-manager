FROM alpine:3.20
RUN apk add --no-cache wireguard-tools-wg-quick iptables jq curl
COPY ./overlay /
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]