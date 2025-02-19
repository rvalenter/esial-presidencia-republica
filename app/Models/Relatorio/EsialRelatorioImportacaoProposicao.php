<?php

namespace App\Models\Relatorio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialRelatorioImportacaoProposicao extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id_externo',
        'sigla',
        'numero',
        'ano',
        'urlinteiroteor',
        'ementa',
        'autor',
        'id_autor',
        'origem',
    ];
}
