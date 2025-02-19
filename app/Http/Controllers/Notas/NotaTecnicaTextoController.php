<?php
declare(strict_types=1);

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notas\NotaTecnicaTextoResource;
use App\Http\Services\Ai\TextAiService;
use App\Http\Services\PDF\PdfService;
use App\Models\Legislativo\EsialLegislativoProposicao;
use Illuminate\Support\Str;

class NotaTecnicaTextoController extends Controller
{
    public function show(EsialLegislativoProposicao $id): NotaTecnicaTextoResource
    {
        $inteiroTeor = $id->inteiroTeor;
        $conteudo = $inteiroTeor->conteudo ?? $this->store($id, $inteiroTeor->link);

        return new NotaTecnicaTextoResource([
            'text' => $conteudo,
            'link' => $inteiroTeor->link,
        ]);
    }

    public function store(EsialLegislativoProposicao $id, string $url): string
    {
        $content = Str::remove('```html', TextAiService::format(PdfService::getContent($url)));
        $id->inteiroTeor()->update(['conteudo' => $content]);

        return $content;
    }
}
