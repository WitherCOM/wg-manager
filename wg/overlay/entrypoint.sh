#!/bin/sh

setup_wireguard() {
  # disable interfaces
  wg-quick down /etc/wireguard/*.conf 

  # clear configs
  rm /etc/wireguard/*.conf 

  # get servers
  response=$(curl "$APP_URL/api/servers" -H "Api-Key: $TOKEN" -H "Content-Type: application/json" | jq -r '.data[] | "\(.name) \(.id)"')

  # create servers
  echo "$response" | while read -r line; do
  # Split the line into name and id
  name=$(echo "$line" | awk '{print $1}')
  id=$(echo "$line" | awk '{print $2}')
  echo "Adding $name"
  curl "$APP_URL/api/server/$id" -H "Api-Key: $TOKEN" -H "Content-Type: application/json" > "/etc/wireguard/$name.conf"
  wg-quick up $name
  done
}

# start coredns 
/usr/bin/coredns -conf /etc/coredns/Corefile -dns.port=53 &

#infinite loop, refresh after 20 minutes
while true
do
  setup_wireguard
  sleep 1200
done