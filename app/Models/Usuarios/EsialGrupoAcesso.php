<?php

namespace App\Models\Usuarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialGrupoAcesso extends Model
{
    use SoftDeletes;

    protected $fillable = ['nome'];

    public function acessos(): HasMany
    {
        return $this->hasMany(EsialAgrupamentoAcesso::class);
    }
}
