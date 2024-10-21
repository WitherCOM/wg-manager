#!/bin/sh

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

#infinite loop
while true
do
  sleep 60
done