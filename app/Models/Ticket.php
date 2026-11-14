<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'device_id',
        'user_id',
        'type',
        'problem_type',
        'error_description',
        'severity',
        'status',
        'comments',
        'scheduled_at',
        'technician_id',
        'solution',
        'solved_at',
    ];

    protected $casts = [
        'solved_at' => 'datetime',
    ];

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
