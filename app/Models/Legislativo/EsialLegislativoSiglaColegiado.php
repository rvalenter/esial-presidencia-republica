<?php

namespace App\Models\Legislativo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsialLegislativoSiglaColegiado extends Model
{
    use HasFactory;

    protected $fillable = [
        'esial_legislativo_proposicao_id',
        'colegiado',
    ];
}
