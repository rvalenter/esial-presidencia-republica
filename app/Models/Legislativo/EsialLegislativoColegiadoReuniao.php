<?php

namespace App\Models\Legislativo;

use App\Enums\Legislativo\ColegiadoReuniaoEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialLegislativoColegiadoReuniao extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'data',
        'hora',
        'local',
        'tipo',
        'esial_legislativo_colegiado_id',
        'observacao',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function tipo(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => ColegiadoReuniaoEnum::from($value)->label(),
        );
    }

    public function colegiado()
    {
        return $this->belongsTo(EsialLegislativoColegiado::class, 'esial_legislativo_colegiado_id');
    }
}
