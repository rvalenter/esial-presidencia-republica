<?php
declare(strict_types=1);

namespace App\Http\Controllers\Relatorio;

use App\Http\Controllers\Controller;
use App\Http\Resources\Relatorio\RelatorioPerfilParlamentarResource;
use App\Models\Contato\EsialContato;
use Spatie\Browsershot\Browsershot;

class RelatorioPerfilParlamentarController extends Controller
{
    public function list(?string $nome = null): RelatorioPerfilParlamentarResource
    {
        $contatos = EsialContato::has('getParlamentar')
            ->has('parlamentarDados')
            ->where('nome', 'iLike', "%$nome%")
            ->with('getPhoto', 'parlamentarDados')
            ->take(5)
            ->get();

        return new RelatorioPerfilParlamentarResource($contatos);
    }

    public function show(EsialContato $id): \Illuminate\View\View
    {
        $contato = $id->load('parlamentarDados')
            ->load(['parlamentarDados.votacoes' => fn ($query) => $query->orderBy('data_votacao', 'desc')]);

        return view('Reports.RelatorioPerfilParlamentar', compact('contato'));
    }

    public function download(EsialContato $id): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $header = view('Reports.layout.header')->render();
        $footer = view('Reports.layout.footer')->render();
        $contato = $id->load('parlamentarDados')
            ->load(['parlamentarDados.votacoes' => fn ($query) => $query->orderBy('data_votacao', 'desc')]);
        $html = view('Reports.RelatorioPerfilParlamentar', compact('contato'))->render();

        $browsershot = Browsershot::html($html)
            ->headerHtml($header)
            ->footerHtml($footer)
            ->showBackground()
            ->margins(20, 10, 15, 10)
            ->format('A3')
            ->noSandbox()
            ->setOption('args', ['--disable-web-security'])
            ->name('Perfil_Parlamentar.pdf')
            ->waitUntilNetworkIdle()
            ->showBrowserHeaderAndFooter(true);

        if (env('APP_ENV') !== 'local') {
            $browsershot->setChromePath('/var/www/Chromium/chromium/linux-1353874/chrome-linux/chrome');
        }

        $browsershot->save('Perfil_Parlamentar.pdf');

        return response()->download(public_path('Perfil_Parlamentar.pdf'));
    }
}
