<?php

namespace Tests\Http\Controllers\Manager;

use App\Http\Controllers\Manager\PropositionExplanationController;
use PHPUnit\Framework\TestCase;

class PropositionExplanationControllerTest extends TestCase
{

    public function test__invoke()
    {
        $esialLegislativoProposicao = new EsialLegislativoProposicao();
        $esialLegislativoProposicao->inteiroTeor = new InteiroTeor();
        $esialLegislativoProposicao->inteiroTeor->link = 'https://example.com';
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

        $pdfService = $this->createMock(PdfService::class);
        $pdfService->expects($this->once())
            ->method('getContent')
            ->with($esialLegislativoProposicao->inteiroTeor->link)
            ->willReturn($text);

        $chat = $this->createMock(Chat::class);
        $chat->expects($this->once())
            ->method('engine')
            ->with("Objetive o texto em no máximo 15 palavras: {$text}")
            ->willReturn('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');

        $controller = new PropositionExplanationController($pdfService, $chat);
        $response = $controller->__invoke($esialLegislativoProposicao);

        $this->assertEquals([
            'explanation' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ], $response->toArray([]));
    }
}
