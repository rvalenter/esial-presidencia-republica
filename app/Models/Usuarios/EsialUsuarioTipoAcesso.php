<?php

namespace App\Models\Usuarios;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EsialUsuarioTipoAcesso extends Model
{
    protected $fillable = [
        'tipo_acesso',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
