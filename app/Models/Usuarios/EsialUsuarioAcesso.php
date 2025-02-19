<?php

namespace App\Models\Usuarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialUsuarioAcesso extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_nivel_acesso_id',
        'esial_usuario_cadastro_id',
        'responsavel',
    ];

    public function nomes()
    {
        return $this->hasMany(EsialNivelAcesso::class, 'id', 'esial_nivel_acesso_id');
    }

    public function NivelAcesso(): BelongsTo
    {
        return $this->belongsTo(EsialNivelAcesso::class, 'esial_nivel_acesso_id');
    }
}
