<?php

namespace App\Models\Nota;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsilNotaTecnicaReferencia extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_nota_tecnica_id',
        'referencia',
    ];

    public function notaTecnica()
    {
        return $this->belongsTo(EsialNotaTecnica::class, 'id', 'esil_nota_tecnica_referencia_id');
    }
}
