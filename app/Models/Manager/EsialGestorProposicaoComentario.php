<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialGestorProposicaoComentario extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_legislativo_proposicao_id',
        'esial_legislativo_colegiado_id',
        'comentario',
        'restrito'
    ];

    public function mencao()
    {
        return $this->hasMany(EsialGestorProposicaoComentarioMencao::class, 'esial_gestor_proposicao_comentario_id');
    }
}
