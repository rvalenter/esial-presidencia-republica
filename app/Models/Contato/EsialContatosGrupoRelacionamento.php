<?php

namespace App\Models\Contato;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialContatosGrupoRelacionamento extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_contatos_grupo_id',
        'esial_contato_id',
    ];

    public function grupo()
    {
        return $this->hasMany(EsialContatosGrupo::class, 'esial_contatos_grupo_id');
    }
}
