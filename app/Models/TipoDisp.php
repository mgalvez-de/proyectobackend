<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDisp extends Model
{
    protected $table = 'tipodisp';

    protected $fillable = [
        'tipo',
    ];

    public $timestamps = false;
}
