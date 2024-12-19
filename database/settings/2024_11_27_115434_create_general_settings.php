<?php

use App\Services\Wireguard;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $wireguard = app(Wireguard::class);
        $this->migrator->add('general.private_key', $wireguard->privateKey());
        $this->migrator->add('general.preshared_key', $wireguard->presharedKey());
    }
};
