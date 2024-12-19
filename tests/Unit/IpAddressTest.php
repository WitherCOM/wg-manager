<?php

namespace Tests\Unit;

use App\Entities\IpAddress;
use Tests\TestCase;

class IpAddressTest extends TestCase
{
    public function test_from_net_mask_number(): void
    {
        $ip = IpAddress::fromMaskNumber(24);

        $this->assertEquals("255.255.255.0", $ip->toString());
    }

    public function test_from_string(): void
    {
        $ip = IpAddress::fromString("10.1.2.3");
        $this->assertEquals("10.1.2.3", $ip->toString());
    }

    public function test_from_bytes(): void
    {
        $ip = IpAddress::fromBytes("\xff\xff\xff\x0");
        $this->assertEquals("255.255.255.0", $ip->toString());
    }

    public function test_from_int(): void
    {
        $ip = IpAddress::fromInt(255);
        $this->assertEquals("0.0.0.255", $ip->toString());
        $this->assertEquals(255, $ip->toInt());
    }
}
