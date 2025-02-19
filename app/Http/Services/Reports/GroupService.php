<?php

namespace App\Http\Services\Reports;

use App\Models\Relatorio\EsialRelatorioImportacaoBancadas;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class GroupService
{
    public static function Bancadas()
    {
        $client = new Client();

        $response = $client->get('https://radar.congressoemfoco.com.br/api/bancadas/', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        collect($result)->each(function ($item) {
            EsialRelatorioImportacaoBancadas::updateOrCreate([
                'id_parlamentar' => $item['id_parlamentar'],
            ], [
                'cod_parlamentar' => self::setIdParlamentar($item['id_parlamentar']),
                'nome' => $item['nome'],
                'partido' => $item['partido'],
                'uf' => $item['uf'],
                'tipo_bancada' => $item['tipo_bancada'],
                'casa' => self::setCasa($item['casa']),
            ]);
        });
    }

    public static function setCasa(string $casa): int
    {
        if ($casa === 'camara') {
            return 1;
        }

        return 2;
    }

    public static function setIdParlamentar(int $id): int
    {
        return (int) Str::after($id, 1);
    }
}
