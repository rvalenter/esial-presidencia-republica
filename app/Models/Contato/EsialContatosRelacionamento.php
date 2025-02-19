<?php

namespace App\Models\Contato;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialContatosRelacionamento extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'responsavel',
        'esial_legislativo_proposicao_id',
    ];

    public function grupo()
    {
        return $this->belongsToMany(EsialContatosGrupoRelacionamento::class, 'esial_contatos_grupo_relacionamentos', 'esial_contato_id', 'esial_contatos_grupo_id');
    }
}
