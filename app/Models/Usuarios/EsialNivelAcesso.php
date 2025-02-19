<?php

namespace App\Models\Usuarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialNivelAcesso extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'acesso_nome',
        'acesso_tipo',
    ];

    public function usuarios(): HasMany
    {
        return $this->hasMany(EsialUsuarioAcesso::class);
    }

    public function acessos(): HasMany
    {
        return $this->hasMany(EsialAgrupamentoAcesso::class);
    }
}
