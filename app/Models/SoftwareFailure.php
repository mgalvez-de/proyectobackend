<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoftwareFailure extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];
}
