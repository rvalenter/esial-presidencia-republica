<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class EsialGestaoProposicaoPosicaoGoverno extends Model
{

    protected $fillable = [
        'esial_legislativo_proposicao_id',
        'esial_legislativo_colegiado_id',
        'justificativa',
        'complemento',
        'tipo_justificativa'
    ];
}
