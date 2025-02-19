<?php

namespace App\Models\Contato;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialContatosFoto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'foto',
        'esial_contato_id',
    ];

    public function contato()
    {
        return $this->belongsToMany(EsialContato::class, 'esial_contatos_fotos', 'id', 'esial_contato_id');
    }
}
