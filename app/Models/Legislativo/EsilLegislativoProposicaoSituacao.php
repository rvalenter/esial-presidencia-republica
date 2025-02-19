<?php

namespace App\Models\Legislativo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsilLegislativoProposicaoSituacao extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'situacao',
    ];

    public function proposicao(): BelongsTo
    {
        return $this->belongsTo(EsialLegislativoProposicao::class);
    }
}
