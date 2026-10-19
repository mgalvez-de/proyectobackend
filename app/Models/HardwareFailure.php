<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HardwareFailure extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];
}
