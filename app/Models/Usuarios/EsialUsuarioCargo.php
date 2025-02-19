<?php

namespace App\Models\Usuarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialUsuarioCargo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
    ];
}
