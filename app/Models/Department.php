<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name'
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
