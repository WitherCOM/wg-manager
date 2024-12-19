[Interface]
Address = {{ $subnet->gateway }}/{{ $subnet->mask }}
ListenPort = {{ $subnet->port }}
PrivateKey = {{ $subnet->private_key }}

# packet forwarding
PostUp = iptables -A FORWARD -i %i -j ACCEPT; iptables -A FORWARD -o %i -j ACCEPT;
PostDown = iptables -D FORWARD -i %i -j ACCEPT; iptables -D FORWARD -o %i -j ACCEPT;

# packet masquerading
PostUp = iptables -t nat -A POSTROUTING -o eth+ -j MASQUERADE
PostDown = iptables -t nat -D POSTROUTING -o eth+ -j MASQUERADE

@bareforeach ($subnet->peers as $peer)
[Peer]
# {{ $peer->name }}
PublicKey = {{ $peer->public_key }}
PresharedKey = {{ $subnet->preshared_key }}
AllowedIPs = {{ $peer->ip_address }}/32
@endbareforeach