<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'name',
        'brand_id',
        'storage_id',
        'department_id'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
