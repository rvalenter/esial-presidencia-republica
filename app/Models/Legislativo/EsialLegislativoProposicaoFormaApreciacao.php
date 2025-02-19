<?php

namespace App\Models\Legislativo;

use App\Enums\Legislativo\FormaEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class EsialLegislativoProposicaoFormaApreciacao extends Model
{
    protected $fillable = [
        'esial_legislativo_proposicao_id',
        'forma',
    ];

    public function forma(): Attribute
    {
        return Attribute::make(
            get: fn(?int $value) => $value !== null ? FormaEnum::from($value)->label() : null,
        );
    }

    public function proposicao(): HasOne
    {
        return $this->hasOne(EsialLegislativoProposicao::class);
    }
}
