<?php

namespace App\Models\Contato;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialContatosGrupo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'responsavel',
        'esial_legislativo_proposicao_id',
        'id_camara',
    ];

    public function relacionamento()
    {
        return $this->belongsToMany(EsialContatosGrupoRelacionamento::class, 'esial_contatos_grupo_relacionamentos', 'esial_contatos_grupo_id', 'esial_contato_id');
    }

    public function Contatos()
    {
        return $this->hasManyThrough(
            EsialContato::class,
            EsialContatosGrupoRelacionamento::class,
            'esial_contatos_grupo_id',
            'id',
            'id',
            'esial_contato_id'
        );
    }
}
