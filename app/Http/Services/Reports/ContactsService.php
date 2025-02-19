<?php

namespace App\Http\Services\Reports;

use App\Http\Services\Image\Image;
use App\Models\Contato\EsialContato;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContactsService
{
    public static function store(array $row): void
    {
        $contact = EsialContato::updateOrCreate([
            'nome' => $row['nomeparlamentar'],
        ], [
            'cargo' => self::checkHouse($row['parlamentar_completo_x'])
                ? 'Deputado Federal'
                : 'Senador Federal',
            'organizacao' => self::checkHouse($row['parlamentar_completo_x'])
                ? 'Camara dos Deputados'
                : 'Senado Federal',
            'telefone' => $row['tel_gabinete'],
            'celular' => $row['tel_particular'],
            'email' => $row['email_x'],
            'endereco' => self::checkHouse($row['parlamentar_completo_x'])
                ? 'Esplanada dos Ministérios, Anexo IV, Brasília - DF'
                : 'Praça dos Três Poderes, Brasília - DF',
            'observacoes' => ! blank($row['chefe_gab'])
                ? Str::title($row['chefe_gab']).' - '.$row['tel_chefe_gab']
                : null,
            'user_id' => Auth::user()->id,
        ]);

        if ($contact->wasRecentlyCreated) {
            try {
                $contact->foto()->sync(self::getPhotoBase64($row['url_foto_x']));
            } catch (\Exception $e) {
                $contact->foto()->sync(null);
            }

            $contact->parlamentar()->sync($row['cod_parlamentar']);
        }
    }

    public static function getPhotoBase64(string $url): string
    {
        $client = new Client();
        $response = $client->get($url);
        $image = Image::resize($response->getBody()->getContents(), true);

        return base64_encode($image);
    }

    public static function checkHouse($house): bool
    {
        if (Str::before($house, '.') === 'DEP') {
            return true;
        }

        return false;
    }
}
