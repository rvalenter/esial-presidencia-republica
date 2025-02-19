<?php
declare(strict_types=1);

namespace App\Http\Controllers\WebServices;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebServices\VotacoesResource;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class VotacoesController extends Controller
{
    public function show(int $id, string $casa): VotacoesResource
    {
        $votosParlamentar = $this->votosParlamentar($id);
        $votosCasa = $this->votos($casa);

        $votes = $votosCasa->flatMap(function ($voto) use ($votosParlamentar) {
            return collect($voto['proposicaoVotacoes'])->map(function ($item) use ($votosParlamentar, $voto) {
                return [
                    'nome' => $voto['nome'],
                    'idProposicao' => $voto['idProposicao'],
                    'objeto' => $item['objetoVotacao'],
                    'secreto' => $item['votacaoSecreta'],
                    'data' => $item['data'],
                    'orientacao' => $this->setPosicaoGoverno($item['orientacao']),
                    'orientacaoNumero' => $item['orientacao'],
                    'voto' => $this->setPosicaoGoverno($votosParlamentar['votos'][$item['idVotacao']] ?? null),
                ];
            });
        })->sortBy([
            ['nome', 'asc'],
            ['data', 'asc'],
        ], SORT_NATURAL);

        return VotacoesResource::make($votes);
    }

    public function votos(string $casa): Collection
    {
        $response = (new Client())->get("https://radar.congressoemfoco.com.br/api/proposicoes/votacoes?casa={$casa}", [
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        return collect(json_decode($response->getBody()->getContents(), true));
    }

    public function votosParlamentar(int $id): Collection
    {
        $response = (new Client())->get("https://radar.congressoemfoco.com.br/api/parlamentares/{$id}/votos", [
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        return collect(json_decode($response->getBody()->getContents(), true));
    }

    public function setPosicaoGoverno(?int $posicao): ?string
    {
        return $posicao === null ? null : [
            -1 => 'Não',
            0 => '-',
            1 => 'Sim',
            3 => 'Livre',
            2 => 'Obstrução',
            5 => 'Liberou',
        ][$posicao];
    }
}
