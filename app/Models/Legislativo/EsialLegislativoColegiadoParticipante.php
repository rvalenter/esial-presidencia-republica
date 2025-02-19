<?php

namespace App\Models\Legislativo;

use App\Models\Contato\EsialContato;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialLegislativoColegiadoParticipante extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_legislativo_colegiado_id',
        'esial_contato_id',
        'cargo',
        'cargo_tipo',
        'codigo_parlamentar',
        'alinhamento',
    ];

    public function contato()
    {
        return $this->belongsTo(EsialContato::class, 'esial_contato_id');
    }
}
