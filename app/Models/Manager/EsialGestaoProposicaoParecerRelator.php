<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class EsialGestaoProposicaoParecerRelator extends Model
{
    protected $fillable = [
        'esial_legislativo_proposicao_id',
        'esial_legislativo_colegiado_id',
        'parecer',
        'tipo',
    ];
}
