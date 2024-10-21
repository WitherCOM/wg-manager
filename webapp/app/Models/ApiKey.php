<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'expires_at'
    ];

    protected static function booted()
    {
        static::creating(function(ApiKey $subnet) {
            $subnet->api_key = Str::random(55);
        });
    }

}
