<?php

namespace App\Models\Legislativo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialLegislativoInteiroTeor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_legislativo_proposicao_id',
        'conteudo',
        'link',
    ];

    public function proposicao()
    {
        return $this->belongsTo(EsialLegislativoProposicao::class);
    }
}
