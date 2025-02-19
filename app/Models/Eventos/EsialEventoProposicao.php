<?php

namespace App\Models\Eventos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialEventoProposicao extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_evento_id',
        'esial_legislativo_proposicao_id',
    ];

    public function event()
    {
        return $this->belongsTo(EsialEvento::class);
    }
}
