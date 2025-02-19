<?php

namespace App\Models\Legislativo;

use App\Enums\Legislativo\OrigemEnum;
use App\Enums\Legislativo\ParlamentarPrioritariasEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialLegislativoProposicaoPrioritaria extends Model
{
    use SoftDeletes;

    public function origem(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => OrigemEnum::from($value)->label(),
        );
    }

    public function tipo(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ParlamentarPrioritariasEnum::from($value)->label(),
        );
    }
}
