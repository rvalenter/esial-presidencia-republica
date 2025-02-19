<?php

namespace App\Models\Relatorio;

use App\Enums\Legislativo\OrigemEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialRelatorioImportacaoBancadas extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id_parlamentar',
        'cod_parlamentar',
        'nome',
        'partido',
        'uf',
        'tipo_bancada',
        'casa',
    ];

    public function origem(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => OrigemEnum::from($value)->label(),
        );
    }
}
