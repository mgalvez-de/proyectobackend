<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    protected $fillable = ['type', 'capacity'];
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
