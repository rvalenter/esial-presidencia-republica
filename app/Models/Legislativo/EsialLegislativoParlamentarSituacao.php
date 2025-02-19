<?php

namespace App\Models\Legislativo;

use App\Enums\Legislativo\ParlamentarSituacaoEnum;
use App\Enums\Legislativo\ParlamentarTipoSituacaoEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialLegislativoParlamentarSituacao extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parlamentar_id',
        'tipo_situacao',
        'situacao',
    ];

    public function situacao(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ParlamentarSituacaoEnum::from($value)->label(),
        );
    }

    public function tipoSituacao(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ParlamentarTipoSituacaoEnum::from($value)->label(),
        );
    }
}
