<?php

namespace App\Models\Legislativo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialLegislativoProposicaoParlamentar extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_legislativo_proposicao_id',
        'esial_legislativo_parlamentar_id',
    ];

    public function proposicao()
    {
        return $this->belongsTo(EsialLegislativoProposicao::class, 'esial_legislativo_proposicao_id');
    }

    public function parlamentar()
    {
        return $this->belongsTo(EsialLegislativoParlamentar::class, 'esial_legislativo_parlamentar_id');
    }
}
