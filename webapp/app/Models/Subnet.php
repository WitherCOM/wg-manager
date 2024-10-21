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
        $min_mask = min(IpAddress::fromString($subnet->mask)->getBytes(), IpAddress::fromString($this->mask)->getBytes());
        return (IpAddress::fromString($subnet->network)->getBytes() & $min_mask) === (IpAddress::fromString($this->network)->getBytes() & $min_mask);
    }

    public function config(): \Illuminate\Contracts\View\View
    {
        return View::make('server_config', ['subnet' => $this]);
    }
}
