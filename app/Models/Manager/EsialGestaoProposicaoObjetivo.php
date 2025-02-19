<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialGestaoProposicaoObjetivo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_gestao_proposicao_geral_id',
        'objetivo'
    ];
}
