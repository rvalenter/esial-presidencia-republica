<?php

namespace App\Models\Relatorio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsialRelatorioImportacaoComissao extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_parlamentar',
        'situacao',
        'sigla',
        'dsc_comissao',
        'cargo_lideranca',
        'unidade_partidaria',
        'classificacao',
        'demais_cargos',
        'cargo_descricao',
        'partido',
        'uf',
        'codigoParlamentar',
        'cod_cargo_hierarquia',
        'sig_casa_comissao',
        'indice_medio',
        'codigo',
    ];
}
