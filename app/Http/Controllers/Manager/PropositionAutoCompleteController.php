<?php
declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Manager\PropositionAutoCompleteResource;
use App\Models\Legislativo\EsialLegislativoProposicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropositionAutoCompleteController extends Controller
{
    public function index(Request $request): PropositionAutoCompleteResource
    {
        $search = preg_replace('/[^A-Za-z0-9\-]/', '', $request->input('arg'));

        return new PropositionAutoCompleteResource(EsialLegislativoProposicao::query()
            ->when($request->filled('arg'), function ($query) use ($request, $search) {
                $query->when(env('DB_CONNECTION') === 'pgsql', function ($query) use ($request, $search) {
                    $query->whereRaw('(sigla || numero || ano) ILIKE ?', ["%{$search}%"]);
                })->when(env('DB_CONNECTION') === 'sqlite', function ($query) use ($request, $search) {
                    $query->whereRaw('(sigla || numero || ano) LIKE ?', ["%{$search}%"]);
                });
            })
            ->orderBy('ano', 'desc')
            ->take(5)
            ->get());
    }

    public function show(int $id): PropositionAutoCompleteResource
    {
        return new PropositionAutoCompleteResource(
            EsialLegislativoProposicao::findOrFail($id)
                ->load([
                    'ementa',
                    'inteiroTeor',
                    'parlamentares.contato',
                    'parlamentares.contato.parlamentarDados',
                    'situacoes',
                    'forma',
                    'relacionamentos',
                    'relacionamentos.ementa',
                    'relacionamentos.inteiroTeor',
                    'relacionamentos.parlamentares.contato',
                    'relacionamentos.parlamentares.contato.parlamentarDados',
                    'relacionamentos.situacoes',
                    'colegiado',
                    'colegiado.dados',
                    'colegiado.parecer',
                ])
        );
    }
}
