<?php

namespace App\Models\Legislativo;

use App\Models\Contato\EsialContato;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialLegislativoParlamentar extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'em_exercicio',
    ];

    public function contato()
    {
        return $this->belongsTo(EsialContato::class, 'esial_contato_id');
    }
}
