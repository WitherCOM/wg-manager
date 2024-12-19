<?php

namespace App\Entities;

class IpAddress {
    
    protected string $bytes = "\x0\x0\x0\x0";

    public static function fromString(string $address)
    {
        $ip = new IpAddress();
        $ip->bytes = inet_pton($address);
        return $ip;
    }

    public static function fromBytes(string $bytes)
    {
        $ip = new IpAddress();
        $ip->bytes = $bytes;
        return $ip;
    }

    public static function fromInt(int $number)
    {
        $ip = new IpAddress();
        $ip->bytes = pack('N', $number);
        return $ip;
    }

    public static function fromMaskNumber(int $mask)
    {
        $ip = new IpAddress();
        $ipNum = 0;
        for ($i = 32; $i >= (32-$mask); $i--)
        {
            $ipNum |= (1 << $i);
        }
        $ip->bytes = pack('N',$ipNum);
        return $ip;
    }

    public function toString(): string
    {
        return inet_ntop($this->bytes);
    }

    public function toBytes(): string
    {
        return $this->bytes;
    }

    public function toInt(): int
    {
        return unpack('N', $this->bytes)[1];
    }
}