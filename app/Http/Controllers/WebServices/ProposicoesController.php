<?php
declare(strict_types=1);

namespace App\Http\Controllers\WebServices;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebServices\ProposicoesResource;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Mtownsend\XmlToArray\XmlToArray;

class ProposicoesController extends Controller
{
    public function show(Request $request): ProposicoesResource
    {
        $client = new Client();
        $response = $client->get("https://dadosabertos.camara.leg.br/api/v2/proposicoes?idDeputadoAutor={$request->id}&ordem=DESC&ordenarPor=id&itens=10000", [
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return ! empty($result['dados']) ? new ProposicoesResource($result) : $this->showSenado($request->id);
    }

    public function showSenado(int $id): ProposicoesResource
    {
        $client = new Client();
        $response = $client->get("https://legis.senado.leg.br/dadosabertos/senador/{$id}/autorias?v=7");
        $parsed = XmlToArray::convert($response->getBody()->getContents());

        $dados = ! empty($parsed['Parlamentar']['Autorias']['Autoria']) ? collect($parsed['Parlamentar']['Autorias']['Autoria'])->map(fn ($item) => [
            'siglaTipo' => $item['Materia']['Sigla'],
            'codTipo' => $item['Materia']['Sigla'],
            'numero' => $item['Materia']['Numero'],
            'ano' => $item['Materia']['Ano'],
            'ementa' => $item['Materia']['Ementa'] ?? '',
        ])->sortByDesc('ano')->values() : [];

        return new ProposicoesResource(['dados' => $dados]);
    }
}
