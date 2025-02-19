<?php

namespace App\Models\Nota;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsialNotaTecnicaAprovacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'esial_nota_tecnica_id',
        'observacao',
        'user_id',
        'aprovador',
    ];

    public function nota()
    {
        return $this->belongsTo(EsialNotaTecnica::class, 'esial_nota_tecnica_id');
    }
}
