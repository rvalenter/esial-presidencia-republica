<?php

namespace App\Imports\Relatorio;

use App\Http\Services\Reports\ProposalService;
use App\Models\Legislativo\EsialLegislativoProposicao;
use App\Models\Relatorio\EsialRelatorioImportacaoProposicao;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EsialRelatorioImportacaoProposicaoImport implements ToModel, WithCustomCsvSettings, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (EsialLegislativoProposicao::where('id_externo', $row['id_externo'])->exists()) {
            return;
        }

        ProposalService::store($row);

        return new EsialRelatorioImportacaoProposicao([
            'id_externo' => $row['id_externo'],
            'sigla' => $row['sigla'],
            'numero' => $row['numero'],
            'ano' => $row['ano'],
            'urlinteiroteor' => $row['urlinteiroteor'],
            'ementa' => $row['ementa'],
            'autor' => $row['autor'],
            'id_autor' => $row['id_autor'],
            'origem' => $row['origem'],
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1',
        ];
    }
}
