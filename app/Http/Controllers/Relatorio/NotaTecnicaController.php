<?php
declare(strict_types=1);

namespace App\Http\Controllers\Relatorio;

use App\Http\Controllers\Controller;
use App\Http\Services\NotaTecnica\NotaTecnicaPosicaoService;
use App\Models\Nota\EsialNotaTecnica;
use Spatie\Browsershot\Browsershot;

class NotaTecnicaController extends Controller
{
    public function download(int $id): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $header = view('Reports.layout.header')->render();
        $footer = view('Reports.layout.footer')->render();
        $data = EsialNotaTecnica::with([
            'orgao',
            'users' => fn ($query) => $query->withTrashed(),
            'analises',
            'posicionamento',
            'impactoIntensidade',
            'impactoTipo',
            'criticos',
            'meritos',
            'conclusoes',
            'anexos',
            'proposicoes.ementa',
            'proposicoes.inteiroTeor',
            'proposicoes.parlamentares.contato',
            'proposicoes.situacoes',
            'proposicoes.tipos',
            'referencias',
            'aprovacoes',
            'area',
            'referenciaRelacaoTable',
            'referenciaRelacaoMany',
        ])->findOrFail($id);
        $border = NotaTecnicaPosicaoService::border($data->esial_nota_tecnica_posicionamento_id);
        $html = view('Reports.RelatorioNotaTecnica', compact(['data', 'border']))->render();
        $browsershot = Browsershot::html($html)
            ->headerHtml($header)
            ->footerHtml($footer)
            ->showBackground()
            ->margins(20, 10, 15, 10)
            ->format('A3')
            ->noSandbox()
            ->setOption('args', ['--disable-web-security'])
            ->name('Comissão.pdf')
            ->waitUntilNetworkIdle()
            ->showBrowserHeaderAndFooter(true);

        if (env('APP_ENV') !== 'local') {
            $browsershot->setChromePath('/var/www/Chromium/chromium/linux-1353874/chrome-linux/chrome');
        }

        $browsershot->save('Nota_Tecnica.pdf');

        return response()->download(public_path('Nota_Tecnica.pdf'));
    }
}
