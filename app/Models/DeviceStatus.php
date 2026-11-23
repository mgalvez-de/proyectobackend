<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceStatus extends Model
{
    protected $fillable = [
        'name'
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    // color del badge según el estado (los nombres vienen del mantenedor de estados)
    public function color(): string
    {
        $name = mb_strtolower($this->name);

        return match (true) {
            str_contains($name, 'operativ') => 'green',
            str_contains($name, 'manten') => 'yellow',
            str_contains($name, 'baja') => 'red',
            default => 'gray',
        };
    }
}
