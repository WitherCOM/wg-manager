[Interface]
Address = {{ $peer->ip_address }}/{{ $peer->subnet->mask }}
PrivateKey = {{ $peer->private_key }}
DNS = {{ $peer->subnet->gateway }}

[Peer]
PublicKey = {{ $peer->subnet->public_key }}
PresharedKey = {{ $peer->subnet->preshared_key }}
Endpoint = {{ $wireguard_ip }}:{{ $peer->subnet->port }}
AllowedIPs = 0.0.0.0/0
