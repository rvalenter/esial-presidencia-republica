<?php

namespace App\Imports\Relatorio;

use App\Http\Services\Reports\ContactsService;
use App\Models\Relatorio\EsialRelatorioImportacaoBaseParlamentar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EsialRelatorioImportacaoBaseParlamentarImport implements ToModel, WithCustomCsvSettings, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        ContactsService::store($row);

        return new EsialRelatorioImportacaoBaseParlamentar([
            'nomeParlamentar' => $row['nomeparlamentar'],
            'siglaPartido' => $row['siglapartido'],
            'uf' => $row['uf'],
            'tel_gabinete' => $row['tel_gabinete'],
            'tel_particular' => $row['tel_particular'],
            'tel_chefe_gab' => $row['tel_chefe_gab'],
            'chefe_gab' => $row['chefe_gab'],
            'tel_pessoal' => $row['tel_pessoal'],
            'cod_bloco' => $row['cod_bloco'],
            'alinhamento' => $row['alinhamento'],
            'avaliacao' => $row['avaliacao'],
            'absoluta' => $row['absoluta'],
            'relativa' => $row['relativa'],
            'ausencia' => $row['ausencia'],
            'cargo_lideranca_y' => $row['cargo_lideranca_y'],
            'siglaPartidoUf' => $row['siglapartidouf'],
            'parlamentar_completo_x' => $row['parlamentar_completo_x'],
            'url_foto_x' => $row['url_foto_x'],
            'email_x' => $row['email_x'],
            'avaliacao_path' => $row['avaliacao_path'],
            'cod_parlamentar' => $row['cod_parlamentar'],
            'percentual_pesos' => $row['percentual_pesos'],
            'obstrucao' => $row['obstrucao'],
            'abstencao' => $row['abstencao'],
            'presidente' => $row['presidente'],
            'contra' => $row['contra'],
            'a_favor' => $row['a_favor'],
            'total' => $row['total'],
            'ausencia_simples' => $row['ausencia_simples'],
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1',
        ];
    }
}
