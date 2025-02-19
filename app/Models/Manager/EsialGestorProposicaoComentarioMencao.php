<?php

namespace App\Models\Manager;

use App\Models\Contato\EsialContato;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialGestorProposicaoComentarioMencao extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_gestor_proposicao_comentario_id',
        'esial_gestor_usuario_id',
    ];

    public function comentario()
    {
        return $this->belongsTo(EsialGestorProposicaoComentario::class, 'esial_gestor_proposicao_comentario_id');
    }

    public function contato()
    {
        return $this->belongsTo(EsialContato::class, 'esial_contato_id');
    }
}
