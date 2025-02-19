<?php

namespace App\Models\Nota;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialNotaTecnicaAnalise extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_nota_tecnica_id',
        'conteudo',
        'user_id',
        'observacao',
        'observacao_user_id',
    ];

    public function notasTecnicas()
    {
        return $this->belongsTo(EsialNotaTecnica::class, 'esial_nota_tecnica_id');
    }
}
