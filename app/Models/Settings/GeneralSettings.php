<?php

namespace App\Models\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings {
    public string $private_key;
    public string $preshared_key;

    public static function group(): string
    {
        return 'general';
    }
}