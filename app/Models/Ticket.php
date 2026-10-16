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
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
