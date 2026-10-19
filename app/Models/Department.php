<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name'
    ];

    // Foto y nombre visible de cada departamento (se usan en el dashboard y en el banner)
    private const DISPLAY = [
        'Informática' => [
            'label' => 'Informática',
            'image' => 'https://facultades.ucsc.cl/content/uploads/sites/25/2023/06/IngEjInformatica-1.jpg',
        ],
        'Biblioteca' => [
            'label' => 'Biblioteca',
            'image' => 'https://upload.wikimedia.org/wikipedia/commons/d/df/Biblioteca_Barzio.jpg',
        ],
        'Computación' => [
            'label' => 'Sala de Computación',
            'image' => 'https://kdoce.cl/wp-content/uploads/2017/03/01-2.jpg',
        ],
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function displayName(): string
    {
        return self::DISPLAY[$this->name]['label'] ?? $this->name;
    }

    public function imageUrl(): ?string
    {
        return self::DISPLAY[$this->name]['image'] ?? null;
    }
}
