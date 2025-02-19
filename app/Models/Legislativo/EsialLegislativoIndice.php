<?php

namespace App\Models\Legislativo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialLegislativoIndice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_legislativo_proposicao_id',
        'palavra_chave',
    ];

    public function proposicao()
    {
        return $this->belongsTo(EsialLegislativoProposicao::class);
    }
}
