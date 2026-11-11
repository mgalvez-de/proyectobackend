<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialty',
        'department_id',
        'user_id',
    ];

    // especialidades posibles (clave guardada => nombre que se muestra)
    public const SPECIALTIES = [
        'hardware' => 'Hardware',
        'software' => 'Software',
        'redes' => 'Redes',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // cuenta con la que el técnico inicia sesión
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function specialtyName(): string
    {
        return self::SPECIALTIES[$this->specialty] ?? $this->specialty;
    }
}
