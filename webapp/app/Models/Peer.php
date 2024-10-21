<?php

namespace App\Models;

use App\Helpers\Wireguard;
use App\Settings\GeneralSettings;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\View;

class Peer extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'ip_address',
        'subnet_id'
    ];

    protected $hidden = [
        'private_key'
    ];

    protected static function booted()
    {
        static::creating(function(Peer $subnet) {
            $subnet->private_key = Wireguard::genPrivateKey();
        });
    }

    public function publicKey(): Attribute
    {
        return Attribute::get(function () {
            return Wireguard::getPublicKey($this->private_key);
        });
    }

    public function subnet(): BelongsTo
    {
        return $this->belongsTo(Subnet::class);
    }

    public function config(): \Illuminate\Contracts\View\View
    {
        return View::make('peer_config', ['peer' => $this, 'wireguard_ip' => app(GeneralSettings::class)->wireguard_ip]);
    }

    public function qr(): string
    {
        $writer = new PngWriter;
        $qr = QrCode::create($this->config()->render())
            ->setMargin(1);
        $result = $writer->write($qr);
        return $result->getDataUri();
    }
}
