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
        'maintenance_type_id',
        'error_description',
        'severity',
        'status',
        'comments',
        'scheduled_at',
        'closed_at',
        'closed_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function maintenanceType()
    {
        return $this->belongsTo(MaintenanceType::class);
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function scopeReactive($query)
    {
        return $query->where('type', 'reactive');
    }

    public function scopePreventive($query)
    {
        return $query->where('type', 'preventive');
    }

    public function isPreventive(): bool
    {
        return $this->type === 'preventive';
    }
}
