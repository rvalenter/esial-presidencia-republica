<?php
declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Manager\PropositionExplanationResource;
use App\Http\Services\Ai\Chat;
use App\Http\Services\PDF\PdfService;
use App\Models\Legislativo\EsialLegislativoProposicao;
use Illuminate\Http\Request;

class PropositionExplanationController extends Controller
{
    public function __invoke(EsialLegislativoProposicao $esialLegislativoProposicao): PropositionExplanationResource
    {
        try {
            $text = PdfService::getContent($esialLegislativoProposicao->inteiroTeor->link);

            return new PropositionExplanationResource([
                'explanation' => Chat::engine("Objetive o texto em no máximo 15 palavras: {$text}"),
            ]);        } catch (\Exception $e) {
            return new PropositionExplanationResource([
                'explanation' => 'Não foi possível encontrar o texto da proposição',
            ]);
        }
    }
}
