<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ram;
class Device extends Model
{
    protected $fillable = [
        'name',
        'brand_id',
        'storage_id',
        'ram_id',
        'department_id'
    ];

    public function ram()
    {
        return $this->belongsTo(Ram::class);
    }

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
