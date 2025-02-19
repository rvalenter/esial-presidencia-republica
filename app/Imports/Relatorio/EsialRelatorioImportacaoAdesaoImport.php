<?php

namespace App\Imports\Relatorio;

use App\Models\Relatorio\EsialRelatorioImportacaoAdesao;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EsialRelatorioImportacaoAdesaoImport implements ToModel, WithCustomCsvSettings, WithHeadingRow
{
    public function model(array $row)
    {
        return new EsialRelatorioImportacaoAdesao([
            'dsc_partido' => $row['dsc_partido'],
            'dsc_casa' => $row['dsc_casa'],
            'adesao_absoluta' => $row['adesao_absoluta'],
            'adesao_relativa' => $row['adesao_relativa'],
            'adesao_media' => $row['adesao_media'],
            'adesao_absoluta_crucial' => $row['adesao_absoluta_crucial'],
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1',
        ];
    }
}
