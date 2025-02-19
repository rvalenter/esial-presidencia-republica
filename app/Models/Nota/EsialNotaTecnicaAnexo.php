<?php

namespace App\Models\Nota;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialNotaTecnicaAnexo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_nota_tecnica_id',
        'nome',
        'conteudo',
        'user_id',
        'nota_tecnica_sei',
    ];

    public function notasTecnicas()
    {
        return $this->belongsTo(EsialNotaTecnica::class, 'esial_nota_tecnica_id');
    }
}
