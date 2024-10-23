# Wireguard manager
## Conception
You have an instance of a running wireguard pod or container. We use container in order to separte network interfaces from the host's interfaces. On the otherhand
there is instance of the web app. You can administrate wireguard peers and subnets this app. The created configurations are stored in the database. On init the wireguard container generates its config based on the database and set the interface up. After 

## Main components

### Web app
[Laravel](https://laravel.com/) based web-app with a [Filament](https://filamentphp.com/docs) enhanced UI and login page. On the page there is a tab where you can define subnets and another where you can add peers to these subnets.

### Wireguard
..


## Setup
### with helm on kubernetes cluster
```
helm upgrade --install -n wg-manager --create-namespace ./helm wg-manager
```