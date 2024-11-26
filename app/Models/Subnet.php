<?php

namespace App\Models;

use App\Helpers\IpAddress;
use App\Helpers\Wireguard;
use App\Settings\GeneralSettings;
use App\Traits\CidrLookup;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class Subnet extends Model
{
    use HasUuids;
    use CidrLookup;

    protected $fillable = [
        'name',
        'network',
        'mask',
        'preshared_key',
        'port'
    ];

    protected $hidden = [
        'private_key'
    ];

    protected static function booted()
    {
        static::creating(function(Subnet $subnet) {
            if (is_null($subnet->preshared_key))
            {
                $subnet->preshared_key = Wireguard::genPresharedKey();
            }
            $subnet->private_key = Wireguard::genPrivateKey();
        });

        static::created(function(Subnet $subnet) {
            $subnet->interfaceUp();
        });

        static::updated(function(Subnet $subnet) {
            $subnet->interfaceUp();
        });

        static::deleted(function(Subnet $subnet) {
            $subnet->interfaceDown();
        });
    }

    public function peers(): HasMany
    {
        return $this->hasMany(Peer::class);
    }

    public function cidr(): Attribute
    {
        return Attribute::get(fn () => $this->network . "/" . $this->mask);
    }

    public function gateway(): Attribute
    {
        return Attribute::get(fn () => IpAddress::fromString($this->network)->next()->toString());
    }

    public function publicKey(): Attribute
    {
        return Attribute::get(function () {
            return Wireguard::getPublicKey($this->private_key);
        });
    }

    public function allocateIp(): string
    {
        $network = IpAddress::fromString($this->network);
        $otherAddress = $this->peers()->pluck('ip_address');
        $address = $network->next();
        $final = null;
        $count = pow(2,32 - $this->mask) - 3;
        for ($i = 0; $i < $count; $i++)
        {
            $address = $address->next();
            if ($address->inSubnet($this) && !$otherAddress->contains($address->toString()))
            {
                $final = $address->toString();
                break;
            }
        }
        return $final;
    }

    public function overlapsWith(Subnet $subnet)
    {
        $min_mask = min(IpAddress::fromMask($subnet->mask)->getBytes(), IpAddress::fromMask($this->mask)->getBytes());
        return (IpAddress::fromString($subnet->network)->getBytes() & $min_mask) === (IpAddress::fromString($this->network)->getBytes() & $min_mask);
    }

    public function interfaceUp()
    {
        $name = Str::of($this->id)->replace("-","")->take(15)->toString();

        // Disable interface if it is up
        exec("wg-quick down $name");

        // Generate config file
        file_put_contents("/etc/wireguard/$name.conf",
            View::make('server_config', ['subnet' => $this])->render());

        // Enable interface
        exec("wg-quick up $name");
    }

    public function interfaceDown()
    {
        $name = Str::of($this->id)->replace("-","")->take(15)->toString();

        // Disable interface if it is up
        exec("wg-quick down $name");

        // Delete config file
        if (file_exists("/etc/wireguard/$name.conf"))
        {
            unlink("/etc/wireguard/$name.conf");
        }

    }
}
