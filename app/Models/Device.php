<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ram;
use App\Models\Brand;
use App\Models\Storage;
use App\Models\Department;
use App\Models\Processor;
use App\Models\DeviceType;


class Device extends Model
{
    protected $fillable = [
        'name',
        'brand_id',
        'device_type_id',
        'storage_id',
        'processor_id',
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


    public function processor()
    {
        return $this->belongsTo(Processor::class);
    }

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

}
