<?php

namespace App\Http\Services\Reports;

use App\Models\Contato\EsialContatosGrupo;
use App\Models\Contato\EsialContatosGrupoRelacionamento;
use App\Models\Legislativo\EsialLegislativoParlamentar;
use App\Models\Relatorio\EsialRelatorioImportacaoBancadas;

class GroupAppService
{
    public static function storeGroupName()
    {
        EsialRelatorioImportacaoBancadas::distinct()->get('tipo_bancada')->each(function ($item) {
            $grupo = EsialContatosGrupo::updateOrCreate([
                'nome' => $item['tipo_bancada'],
                'responsavel' => 1,
            ], [
                'id_camara' => null,
            ])->id;

            self::storeUserGroup($grupo, $item['tipo_bancada']);
        });
    }

    public static function storeUserGroup(int $grupo, string $nome)
    {
        EsialRelatorioImportacaoBancadas::where('tipo_bancada', $nome)->get()->each(function ($item) use ($grupo) {
            $contatoId = self::setIdParlamentary($item['cod_parlamentar']);

            if ($contatoId) {
                EsialContatosGrupoRelacionamento::updateOrCreate([
                    'esial_contatos_grupo_id' => $grupo,
                    'esial_contato_id' => $contatoId->esial_contato_id,
                ]);
            }
        });
    }

    public static function setIdParlamentary(int $parlamentary)
    {
        return EsialLegislativoParlamentar::where('codigo', $parlamentary)->first();
    }
}
