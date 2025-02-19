<?php
declare(strict_types=1);

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notas\NotaTecnicaAiResource;
use App\Http\Services\Ai\TextAiService;
use App\Models\Nota\EsialNotaTecnica;
use Illuminate\Http\Request;

class NotaTecnicaAiController extends Controller
{
    public function generateIA(Request $request): NotaTecnicaAiResource
    {
        $tecnicalNote = EsialNotaTecnica::find($request->id);
        $text = $tecnicalNote->proposicoes->inteiroTeor->conteudo;
        $position = $tecnicalNote->posicao->posicionamento;
        $working = $this->setWorking($request->working);
        $organization = $tecnicalNote->orgao->nome;

        return new NotaTecnicaAiResource([
            TextAiService::generate("Atue como analista legislativo experiente do órgão {$organization}, analise o texto a seguir e {$working}, a resposta deve possuir elevado grau de retórica e carater {$position} ao texto em sua essência, possuindo no máximo 700 caracteres: {$text}"),
        ]);
    }

    public function setWorking(int $working): string
    {
        if ($working == 2) {
            return 'descreva os pontos centrais fazendo correlações entre o texto e o orgão';
        }

        if ($working == 3) {
            return 'faça a análise de mérito carrelacionando o texto e o orgão';
        }

        return 'escreva uma conclusão carrelacionando o texto e o orgão';
    }
}
