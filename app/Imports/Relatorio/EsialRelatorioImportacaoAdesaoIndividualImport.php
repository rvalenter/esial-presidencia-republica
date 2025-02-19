<?php

namespace App\Imports\Relatorio;

use App\Models\Relatorio\EsialRelatorioImportacaoAdesaoIndividual;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EsialRelatorioImportacaoAdesaoIndividualImport implements ToModel, WithCustomCsvSettings, WithHeadingRow
{
    public function model(array $row)
    {
        return new EsialRelatorioImportacaoAdesaoIndividual([
            'codigoParlamentar' => $row['codigoparlamentar'],
            'dsc_casa' => $row['dsc_casa'],
            'adesaoAbsolutaCrucial' => $row['adesaoabsolutacrucial'],
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1',
        ];
    }
}
