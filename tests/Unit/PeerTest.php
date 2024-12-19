<?php

namespace Tests\Unit;

use App\Models\Peer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PeerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Config::set('wireguard.subnet.network','10.1.1.0');
        Config::set('wireguard.subnet.gateway','10.1.1.1');
        Config::set('wireguard.subnet.mask',24);
    }

    public function test_allocate_ip()
    {
        $peer = new Peer();
        $this->assertTrue($peer->allocateIp());
        $this->assertEquals('10.1.1.2', $peer->ip_address->toString());
    }

    public function test_allocate_max_ip()
    {
        $peer = new Peer();
        $this->assertTrue($peer->allocateIp());
        $this->assertEquals('10.1.1.2', $peer->ip_address->toString());
    }

}
