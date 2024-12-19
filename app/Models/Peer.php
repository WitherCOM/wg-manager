<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\IpAddressCast;
use App\Entities\IpAddress;

class Peer extends Model
{
    /** @use HasFactory<\Database\Factories\PeerFactory> */
    use HasFactory;
    use HasUuids;

     protected $filleable = [
        'name'
     ];

     protected $hidden = [
        'private_key'
     ];

     protected $casts = [
        'ip_address' => IpAddressCast::class     
     ];

     public function allocateIp(): bool
     {
        $otherAddresses = Peer::whereNot('id',$this->id)->get()->pluck('ip_address');
        $otherAddresses->push(IpAddress::fromString(config('wireguard.subnet.gateway')));
        for ($i = 1; $i < (32 - config('wireguard.subnet.mask')); $i++)
        {
            $ip_address = IpAddress::fromInt(IpAddress::fromString(config('wireguard.subnet.network'))->toInt() + $i);
            if (!$otherAddresses->contains($ip_address))
            {
                $this->ip_address = $ip_address;
                return true;
            }
        }
        return false;
     }
}
