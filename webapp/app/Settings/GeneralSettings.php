<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $wireguard_ip;

    public static function group(): string
    {
        return 'general';
    }
}
