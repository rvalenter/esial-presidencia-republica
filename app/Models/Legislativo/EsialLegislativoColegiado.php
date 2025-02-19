<?php

namespace App\Models\Legislativo;

use App\Enums\Legislativo\OrigemEnum;
use App\Models\Relatorio\EsialRelatorioImportacaoComissao;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialLegislativoColegiado extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sigla',
        'descricao',
        'codigo',
        'origem',
        'composicao',
        'indice',
    ];

    public function origem(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => OrigemEnum::from($value)->label(),
        );
    }

    public function participantes()
    {
        return $this->hasMany(EsialLegislativoColegiadoParticipante::class);
    }

    public function reunioes()
    {
        return $this->hasMany(EsialLegislativoColegiadoReuniao::class);
    }

    public function dados()
    {
        return $this->hasMany(EsialRelatorioImportacaoComissao::class, 'codigo', 'codigo');
    }

    public function colegiadoDados()
    {
        return $this->hasOne(EsialLegislativoColegiado::class);
    }
}
