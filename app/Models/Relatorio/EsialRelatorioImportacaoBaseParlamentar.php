<?php

namespace App\Models\Relatorio;

use Illuminate\Database\Eloquent\Model;

class EsialRelatorioImportacaoBaseParlamentar extends Model
{
    protected $fillable = [
        'nomeParlamentar',
        'siglaPartido',
        'uf',
        'tel_gabinete',
        'tel_particular',
        'tel_chefe_gab',
        'chefe_gab',
        'tel_pessoal',
        'cod_bloco',
        'alinhamento',
        'avaliacao',
        'absoluta',
        'relativa',
        'ausencia',
        'cargo_lideranca_y',
        'siglaPartidoUf',
        'parlamentar_completo_x',
        'url_foto_x',
        'email_x',
        'avaliacao_path',
        'cod_parlamentar',
        'percentual_pesos',
        'obstrucao',
        'abstencao',
        'presidente',
        'contra',
        'a_favor',
        'total',
        'ausencia_simples',
    ];

    public function votacoes()
    {
        return $this->hasMany(EsialRelatorioImportacaoVotacao::class, 'cod_parlamentar', 'cod_parlamentar');
    }

    public function getAvaliacaoAttribute($value)
    {
        if ($value == 'OP') {
            return 'Oposição - OP';
        }

        if ($value == 'AC') {
            return 'Apoio Condicional - AC';
        }

        if ($value == 'DS') {
            return 'Oposicao em Disputa - DS';
        }

        if ($value == 'BG') {
            return 'Base Governo - BG';
        }

        return 'Sem Avaliação';
    }

    public function getCargoLiderancaYAttribute($value)
    {
        return explode(';', $value);
    }
}
