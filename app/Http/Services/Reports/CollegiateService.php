<?php

namespace App\Http\Services\Reports;

use App\Models\Contato\EsialContato;
use App\Models\Legislativo\EsialLegislativoColegiado;
use App\Models\Legislativo\EsialLegislativoColegiadoParticipante;

class CollegiateService
{
    public static function Collegiate(array $row)
    {
        $collegiate = EsialLegislativoColegiado::updateOrCreate([
            'sigla' => $row['sigla'],
            'origem' => $row['sig_casa_comissao'] ? 1 : 2,
        ], [
            'descricao' => $row['dsc_comissao'],
            'codigo' => $row['codigo'],
            'composicao' => $row['unidade_partidaria'],
            'indice' => $row['indice_medio'],
        ]);

        self::Participants($collegiate, $row);
    }

    public static function Participants(EsialLegislativoColegiado $collegiate, array $row)
    {
        $contato = EsialContato::whereHas('parlamentarDados', function ($query) use ($row) {
            $query->where('cod_parlamentar', $row['codigoparlamentar']);
        })->first();

        EsialLegislativoColegiadoParticipante::updateOrCreate([
            'esial_legislativo_colegiado_id' => $collegiate->id,
            'codigo_parlamentar' => $row['codigoparlamentar'],
        ], [
            'alinhamento' => $row['classificacao'],
            'cargo' => $row['cargo_descricao'],
            'cargo_tipo' => $row['situacao'],
            'esial_contato_id' => $contato->id ?? null,
        ]);
    }
}
