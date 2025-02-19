<?php
declare(strict_types=1);

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notas\NotaTecnicaResumoResource;
use App\Http\Services\Ai\TextAiService;
use App\Http\Services\PDF\PdfService;
use App\Models\Legislativo\EsialLegislativoProposicao;
use Illuminate\Support\Str;

class NotaTecnicaResumoController extends Controller
{
    public function show(EsialLegislativoProposicao $id): NotaTecnicaResumoResource
    {
        $resumo = $id->inteiroTeor->resumo ?? $this->store($id, $id->inteiroTeor->link);

        return new NotaTecnicaResumoResource([$resumo]);
    }

    public function store(EsialLegislativoProposicao $id, string $url): string
    {
        $content = Str::remove('```html', TextAiService::summarize(PdfService::getContent($url)));
        $id->inteiroTeor()->update(['resumo' => $content]);

        return $content;
    }
}
