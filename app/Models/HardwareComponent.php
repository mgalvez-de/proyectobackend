<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Pieza de un dispositivo (placa madre, pantalla, gabinete o fuente de poder)
class HardwareComponent extends Model
{
    protected $fillable = [
        'device_id',
        'type',
        'brand_id',
        'model',
        'serial_number',
        'installed_at',
        'expires_at',
    ];

    protected $casts = [
        'installed_at' => 'date',
        'expires_at' => 'date',
    ];

    // tipos posibles (clave guardada => nombre que se muestra)
    public const TYPES = [
        'motherboard' => 'Placa madre',
        'screen' => 'Pantalla / monitor',
        'case' => 'Gabinete',
        'power_supply' => 'Fuente de poder',
    ];

    // días antes del vencimiento en que se avisa "vence pronto"
    public const EXPIRING_DAYS = 90;

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function typeName(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    // vigente, vence pronto o vencido, según la fecha de vencimiento
    public function expirationStatus(): ?string
    {
        if (! $this->expires_at) {
            return null;
        }

        if ($this->expires_at->isPast()) {
            return 'expired';
        }

        return $this->expires_at->lte(now()->addDays(self::EXPIRING_DAYS)) ? 'expiring' : 'ok';
    }
}
