<?php

namespace App\Imports\Relatorio;

use App\Http\Services\Reports\CollegiateService;
use App\Models\Relatorio\EsialRelatorioImportacaoComissao;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EsialRelatorioImportacaoComissaoImport implements ToModel, WithCustomCsvSettings, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        CollegiateService::Collegiate($row);

        return new EsialRelatorioImportacaoComissao([
            'nome_parlamentar' => $row['nome_parlamentar'],
            'situacao' => $row['situacao'],
            'sigla' => $row['sigla'],
            'dsc_comissao' => $row['dsc_comissao'],
            'cargo_lideranca' => $row['cargo_lideranca'],
            'unidade_partidaria' => $row['unidade_partidaria'],
            'classificacao' => $row['classificacao'],
            'demais_cargos' => $row['demais_cargos'],
            'cargo_descricao' => $row['cargo_descricao'],
            'partido' => $row['partido'],
            'uf' => $row['uf'],
            'codigoParlamentar' => $row['codigoparlamentar'],
            'cod_cargo_hierarquia' => $row['cod_cargo_hierarquia'],
            'sig_casa_comissao' => $row['sig_casa_comissao'],
            'indice_medio' => $row['indice_medio'],
            'codigo' => $row['codigo'],
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1',
        ];
    }
}
