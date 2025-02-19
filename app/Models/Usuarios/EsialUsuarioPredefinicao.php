<?php

namespace App\Models\Usuarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialUsuarioPredefinicao extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_usuario_cadastro_id',
        'esial_grupo_acesso_id',
        'responsavel',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(EsialUsuarioCadastro::class, 'esial_usuario_cadastro_id');
    }

    public function grupoAcesso(): BelongsTo
    {
        return $this->belongsTo(EsialGrupoAcesso::class, 'esial_grupo_acesso_id');
    }
}
