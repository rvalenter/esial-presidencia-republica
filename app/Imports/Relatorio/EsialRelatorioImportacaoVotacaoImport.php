<?php

namespace App\Imports\Relatorio;

use App\Models\Relatorio\EsialRelatorioImportacaoVotacao;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EsialRelatorioImportacaoVotacaoImport implements ToModel, WithCustomCsvSettings, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new EsialRelatorioImportacaoVotacao([
            'dsc_votacao' => $row['dsc_votacao'],
            'data_votacao' => $row['data_votacao'],
            'hora_votacao' => $row['hora_votacao'],
            'dsc_voto' => $row['dsc_voto'],
            'dsc_orientacao_partido' => $row['dsc_orientacao_partido'],
            'dsc_orientacao_governo' => $row['dsc_orientacao_governo'],
            'dsc_peso' => $row['dsc_peso'],
            'dsc_casa' => $row['dsc_casa'],
            'dsc_apelido' => $row['dsc_apelido'],
            'cod_parlamentar' => $row['cod_parlamentar'],
            'cod_votacao' => $row['cod_votacao'],
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1',
        ];
    }
}
