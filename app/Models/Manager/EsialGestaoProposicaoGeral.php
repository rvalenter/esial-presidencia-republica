<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialGestaoProposicaoGeral extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "esial_legislativo_proposicao_id",
        "esial_usuario_cadastro_id",
        "casa_atual",
        "apelido",
        "prioritaria",
        "evento"
    ];
}
