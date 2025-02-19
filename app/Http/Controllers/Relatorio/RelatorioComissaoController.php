<?php
declare(strict_types=1);

namespace App\Http\Controllers\Relatorio;

use App\Http\Controllers\Controller;
use App\Http\Resources\Relatorio\RelatorioComissaoResource;
use App\Models\Legislativo\EsialLegislativoColegiado;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Spatie\Browsershot\Browsershot;

class RelatorioComissaoController extends Controller
{
    public bool $access = false;

    public function __construct()
    {
        $user = auth()->user();
        $this->access = $user->hasPermission([16]);
    }

    public function index(): RelatorioComissaoResource
    {
        return RelatorioComissaoResource::make(EsialLegislativoColegiado::with('participantes')->whereHas(
            'participantes',
            function ($query) {
                $query->where('cargo', 'Presidente');
            }
        )->get());
    }

    public function show(EsialLegislativoColegiado $id): \Illuminate\View\View
    {
        $agent = new Agent();

        $comissao = $id->load([
            'participantes' => function ($query) {
                $query->where('cargo_tipo', 'Titular')
                    ->whereNotNull('esial_contato_id');
            },
        ]);

        $presidente = $comissao->participantes->where('cargo', 'Presidente')->first();

        return view('Reports.RelatorioComissao', [
            'comissao' => $comissao,
            'presidente' => $presidente,
            'presidenteClass' => $this->setRing($presidente),
            'membros' => $comissao->participantes->where('cargo', 'Membro'),
            'vicePresidente' => $this->setVicePresidente($comissao->participantes),
            'countByAlinhamento' => $this->setPercentByAlinhamento($comissao->participantes),
            'isMobile' => $agent->isMobile(),
            'isManager' => $this->access,
        ]);
    }

    public function download(EsialLegislativoColegiado $id): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $comissao = $id->load(['participantes' => function ($query) {
            $query->where('cargo_tipo', 'Titular')
                ->whereNotNull('esial_contato_id');
        }]);
        $presidente = $comissao->participantes->where('cargo', 'Presidente')->first();

        $header = view('Reports.layout.header')->render();
        $footer = view('Reports.layout.footer')->render();
        $html = view('Reports.RelatorioComissao', [
            'comissao' => $comissao,
            'presidente' => $presidente,
            'presidenteClass' => $this->setRing($presidente),
            'membros' => $comissao->participantes->where('cargo', 'Membro'),
            'vicePresidente' => $this->setVicePresidente($comissao->participantes),
            'countByAlinhamento' => $this->setPercentByAlinhamento($comissao->participantes),
            'isManager' => $this->access,
        ])->render();

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

        $browsershot->save('Comissão.pdf');

        return response()->download(public_path('Comissão.pdf'));
    }

    public function setPercentByAlinhamento($participants): \Illuminate\Support\Collection
    {
        $amount = $participants->groupBy('alinhamento')->map->count();

        return $amount->map(function ($item) use ($participants) {
            return [
                'percentage' => number_format($item / $participants->count() * 100, 0, '.', ''),
                'amount' => $item,
            ];
        });
    }

    public function setVicePresidente($vicePresidente): \Illuminate\Support\Collection
    {
        return $vicePresidente->filter(function ($participante) {
            return Str::contains(Str::lower($participante->cargo), 'vice');
        })
            ->sortBy('cargo')
            ->map(function ($vice) {
                return [
                    'nome' => $vice->contato['nome'],
                    'foto' => $vice->contato->parlamentarDados['url_foto_x'],
                    'partido' => $vice->contato->parlamentarDados['siglaPartidoUf'],
                    'cargo' => $vice['cargo'],
                    'ringClass' => $this->setRing($vice),
                ];
            });
    }

    public function setRing($president): string
    {
        if (!$this->access) {
            return '';
        }

        $alinhamento = $president->alinhamento ?? '';

        if ($alinhamento == 'BG') {
            return 'border-green-600';
        }

        if ($alinhamento == 'AC') {
            return 'border-lime-400';
        }

        if ($alinhamento == 'DS') {
            return 'border-yellow-500';
        }

        return 'border-red-500';
    }
}
