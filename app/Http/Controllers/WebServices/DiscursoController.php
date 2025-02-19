<?php
declare(strict_types=1);

namespace App\Http\Controllers\WebServices;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Mtownsend\XmlToArray\XmlToArray;
use Symfony\Component\DomCrawler\Crawler;

class DiscursoController extends Controller
{
    public static function show(int $id, int $type): \Illuminate\Support\Collection
    {
        return $type == 1 ? self::camara($id) : self::senado($id);
    }

    public static function camara(int $id): \Illuminate\Support\Collection
    {
        $now = Carbon::now()->format('Y-m-d');
        $url = "https://dadosabertos.camara.leg.br/api/v2/deputados/{$id}/discursos?dataInicio=2024-01-01&dataFim={$now}&ordenarPor=dataHoraInicio&ordem=DESC&itens=100";

        $client = new Client();
        $response = $client->get("$url", [
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        return collect(json_decode($response->getBody()->getContents(), true))
            ->flatten(1)
            ->map(fn ($item) => empty($item['transcricao']) && empty($item['sumario']) ? null : [
                'data' => Carbon::parse($item['dataHoraInicio'])->format('d/m/Y') ?? 'Data não disponível',
                'resumo' => $item['sumario'] ?? 'Resumo não disponível',
                'texto' => $item['transcricao'] ?? 'Texto não disponível',
            ])
            ->filter()
            ->sortByDesc(fn ($item) => Carbon::createFromFormat('d/m/Y', $item['data']));
    }

    public static function senado(int $id): \Illuminate\Support\Collection
    {
        $client = new Client();
        $endDate = Carbon::now()->format('Ymd');
        $response = $client->get("https://legis.senado.leg.br/dadosabertos/senador/{$id}/discursos?dataInicio=20150301&dataFim={$endDate}", [
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        $discursos = XmlToArray::convert($response->getBody()->getContents());

        if (collect($discursos['Parlamentar']['Pronunciamentos'])->isEmpty()) {
            return collect([]);
        }

        return collect($discursos['Parlamentar']['Pronunciamentos']['Pronunciamento'])
            ->map(fn ($item) => [
                'texto' => self::getFullTextSenado($item['UrlTexto']),
                'data' => Carbon::parse($item['DataPronunciamento'])->format('d/m/Y') ?? 'Data não disponível',
                'resumo' => $item['TextoResumo'] ?? 'Resumo não disponível',
            ])
            ->filter()
            ->sortByDesc(fn ($item) => Carbon::createFromFormat('d/m/Y', $item['data']));
    }

    public static function getFullTextSenado(string $url): string
    {
        $client = new Client();
        $response = $client->get($url);
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html);

        return $crawler->filter('div.texto-integral')->count() ? $crawler->filter('div.texto-integral')->text() : '';
    }

    public static function parseSenadoDiscurso(string $texto): string
    {
        return trim(preg_replace('/^\s*$/m', '', preg_replace('/(\\\u\{A0\})+/', "\n", $texto)));
    }
}
