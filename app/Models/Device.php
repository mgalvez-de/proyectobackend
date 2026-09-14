<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }
}

