<?php
declare(strict_types=1);

namespace App\Http\Services\Reports;

use App\Models\Contato\EsialContatosGrupo;
use App\Models\Contato\EsialContatosGrupoRelacionamento;
use App\Models\Legislativo\EsialLegislativoParlamentar;
use GuzzleHttp\Client;

class ParliamentaryFrontService
{
    public static function Fronts(): void
    {
        set_time_limit(0);

        $client = new Client();

        $response = $client->get('https://dadosabertos.camara.leg.br/api/v2/frentes', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        collect($result['dados'])->each(function ($item) {
            $id = EsialContatosGrupo::updateOrCreate([
                'id_camara' => $item['id'],
            ], [
                'nome' => $item['titulo'],
                'responsavel' => 1,
            ])->id;

            self::Parliamentaries($id, $item['id']);
        });
    }

    public static function Parliamentaries(int $id, int $idCamara): void
    {
        $client = new Client();

        $response = $client->get("https://dadosabertos.camara.leg.br/api/v2/frentes/{$idCamara}/membros", [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        collect($result['dados'])->each(function ($item) use ($id) {
            $contatoId = self::setIdParlamentary($item['id']);

            if ($contatoId) {
                EsialContatosGrupoRelacionamento::updateOrCreate([
                    'esial_contatos_grupo_id' => $id,
                    'esial_contato_id' => $contatoId->esial_contato_id,
                ]);
            }
        });
    }

    public static function setIdParlamentary(int $parlamentary): ?EsialLegislativoParlamentar
    {
        return EsialLegislativoParlamentar::where('codigo', $parlamentary)->first();
    }
}
