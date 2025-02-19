<?php

namespace App\Models\Nota;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialNotaTecnicaReferenciaRelacao extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_nota_tecnica_id',
        'referencia',
        'complemento',
        'esial_nota_tecnica_referencia_preencimento_id',
        'contexto',
    ];
}
