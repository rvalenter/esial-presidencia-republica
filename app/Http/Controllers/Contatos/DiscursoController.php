<?php
declare(strict_types=1);

namespace App\Http\Controllers\Contatos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WebServices\DiscursoController as DiscursoControllerWS;
use App\Http\Resources\Contatos\DiscursoResource;
use App\Http\Services\Ai\TextAiService;
use Illuminate\Http\Request;

class DiscursoController extends Controller
{
    public function show(int $id, int $type): DiscursoResource
    {
        return new DiscursoResource(DiscursoControllerWS::show($id, $type));
    }

    public function analyse(Request $request): DiscursoResource
    {
        $text = $request->input('texto');
        $analysis = TextAiService::generate(
            "Baseado no discurso que segue, faça a análise atuando como um experiente analista legislativo, classifique quanto ao espectro politico seja claro, sucinto e não agressivo. Não exceda 5 linhas. Insira um novo parágrafo como o percentual de aderencia deste discurso ao governo federal do PT, máximo 3 linhas: {$text}"
        );

        return new DiscursoResource([$analysis]);
    }
}
