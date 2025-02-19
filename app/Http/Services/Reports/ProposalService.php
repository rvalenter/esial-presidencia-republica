<?php

namespace App\Http\Services\Reports;

use App\Models\Legislativo\EsialLegislativoEmenta;
use App\Models\Legislativo\EsialLegislativoInteiroTeor;
use App\Models\Legislativo\EsialLegislativoParlamentar;
use App\Models\Legislativo\EsialLegislativoProposicao;
use App\Models\Legislativo\EsialLegislativoProposicaoParlamentar;

class ProposalService
{
    public static function store(array $row): void
    {
        $proposicao = EsialLegislativoProposicao::create([
            'id_externo' => $row['id_externo'],
            'sigla' => $row['sigla'],
            'numero' => $row['numero'],
            'ano' => $row['ano'],
            'origem' => $row['origem'],
        ]);

        if ($proposicao && $row['ementa']) {
            self::ementa($row, $proposicao);
        }

        if ($proposicao && $row['urlinteiroteor']) {
            self::inteiroTeor($row, $proposicao);
        }

        if ($proposicao && $row['id_autor']) {
            self::parlamentar($row, $proposicao);
        }
    }

    public static function ementa(array $row, EsialLegislativoProposicao $proposicao)
    {
        EsialLegislativoEmenta::create([
            'esial_legislativo_proposicao_id' => $proposicao->id,
            'conteudo' => $row['ementa'],
        ]);
    }

    public static function inteiroTeor(array $row, EsialLegislativoProposicao $proposicao)
    {
        EsialLegislativoInteiroTeor::create([
            'esial_legislativo_proposicao_id' => $proposicao->id,
            'link' => $row['urlinteiroteor'],
        ]);
    }

    public static function parlamentar(array $row, EsialLegislativoProposicao $proposicao)
    {
        if (is_int($row['id_autor'])) {
            $parlamentar = EsialLegislativoParlamentar::where('codigo', $row['id_autor'])->first();

            if ($parlamentar && $proposicao) {
                EsialLegislativoProposicaoParlamentar::create([
                    'esial_legislativo_proposicao_id' => $proposicao->id,
                    'esial_legislativo_parlamentar_id' => $parlamentar->id,
                ]);
            }
        }
    }
}
