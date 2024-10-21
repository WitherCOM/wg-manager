<?php

namespace App\Helpers;

use App\Models\Subnet;

class IpAddress
{
    protected string $bytes = "\x0\x0\x0\x0";

    public function __construct(int $address_int = null, string $address_string = null, string $address_bytes = null)
    {
        if ($address_int !== null) {
            $this->bytes = pack('N', $address_int);
        } elseif ($address_string !== null) {
            $this->bytes = inet_pton($address_string);
        } elseif ($address_bytes !== null && strlen($address_bytes) === 4) {
            $this->bytes = $address_bytes;
        }
    }

    public static function fromInt(int $address_int): IpAddress
    {
        return new IpAddress(address_int: $address_int);
    }

    public static function fromString(string $address_string): IpAddress
    {
        return new IpAddress(address_string: $address_string);
    }

    public function getBytes(): string
    {
        return $this->bytes;
    }

    public function toString(): string
    {
        return inet_ntop($this->bytes);
    }

    public function next(): IpAddress
    {
        return static::fromInt(unpack('N', $this->bytes)[1] + 1);
    }

    public function inSubnet(Subnet $subnet): bool
    {
        $network = self::fromString($subnet->network)->getBytes();
        $first = self::fromString($subnet->network)->next()->getBytes();
        $mask = self::fromString(Subnet::getCidrLookup()[$subnet->mask])->getBytes();
        $broadcast = ~$mask | $network;
        return ($this->bytes & $mask) === $network && $this->bytes !== $network && $this->bytes !== $broadcast && $this->bytes != $first;
    }


}
