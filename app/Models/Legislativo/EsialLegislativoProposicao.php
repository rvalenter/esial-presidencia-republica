<?php

namespace App\Models\Legislativo;

use App\Enums\Legislativo\OrigemEnum;
use App\Enums\Legislativo\RegimeEnum;
use App\Models\Nota\EsialNotaTecnica;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class EsialLegislativoProposicao extends Model
{

    use SoftDeletes, Searchable;

    protected $fillable = [
        'sigla',
        'numero',
        'ano',
        'origem',
        'id_externo',
    ];

    public function origem(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => OrigemEnum::from($value)->label(),
        );
    }

    public function regime(): Attribute
    {
        return Attribute::make(
            get: fn(?int $value) => $value !== null ? RegimeEnum::from($value)->label() : null,
        );
    }

    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        $array['all'] = $this->sigla . $this->numero . $this->ano;

        return $array;
    }

    public function notaTecnica(): HasMany
    {
        return $this->hasMany(EsialNotaTecnica::class);
    }

    public function ementa(): HasOne
    {
        return $this->hasOne(EsialLegislativoEmenta::class);
    }

    public function inteiroTeor(): HasOne
    {
        return $this->hasOne(EsialLegislativoInteiroTeor::class);
    }

    public function indices(): HasOne
    {
        return $this->hasOne(EsialLegislativoIndice::class);
    }

    public function situacoes(): HasOne
    {
        return $this->hasOne(EsilLegislativoProposicaoSituacao::class)->latest();
    }

    public function tipos(): HasOne
    {
        return $this->hasOne(EsilLegislativoProposicaoTipo::class);
    }

    public function parlamentares(): HasManyThrough
    {
        return $this->hasManyThrough(
            EsialLegislativoParlamentar::class,
            EsialLegislativoProposicaoParlamentar::class,
            'esial_legislativo_proposicao_id',
            'id',
            'id',
            'esial_legislativo_parlamentar_id'
        );
    }

    public function relacionamentos(): HasManyThrough
    {
        return $this->hasManyThrough(
            EsialLegislativoProposicao::class,
            EsialLegislativoRelacionamento::class,
            'esial_legislativo_proposicao_id',
            'id',
            'id',
            'id'
        );
    }

    public function forma(): HasOne
    {
        return $this->hasOne(EsialLegislativoProposicaoFormaApreciacao::class);
    }

    public function colegiado(): HasMany
    {
        return $this->hasMany(EsialLegislativoColegiadoProposicao::class, 'esial_legislativo_proposicao_id');
    }
}
