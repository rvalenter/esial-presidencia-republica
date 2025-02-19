<?php
declare(strict_types=1);

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notas\NotaTecnicaDashboardResource;
use App\Models\Nota\EsialNotaTecnica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotaTecnicaDashboardController extends Controller
{
    public function index(Request $request): NotaTecnicaDashboardResource
    {
        $orgao = Auth::user()->DadosCadastrais->esial_usuario_orgao_id;

        return new NotaTecnicaDashboardResource(
            EsialNotaTecnica::with('posicionamento')
                ->whereHas('posicionamento')
                ->where('esial_usuario_orgao_id', $orgao)
                ->when($request->input('status') !== 'Todos' && $request->input('status') != '2', fn ($query) => $query->where('concluida', $request->input('status')))
                ->when($request->input('status') == '2', fn ($query) => $query->onlyTrashed())
                ->when($request->input('search'), fn ($query) => $query->whereHas('proposicoes', fn ($query) => $query->where(DB::raw('(sigla || numero || ano)'), 'ilike', '%'.$request->input('search').'%')))
                ->get()
                ->groupBy('posicionamento.posicionamento')
                ->map(fn ($group) => $group->count())
                ->sortKeys()
        );
    }

    public function table(Request $request): NotaTecnicaDashboardResource
    {
        $orgao = Auth::user()->DadosCadastrais->esial_usuario_orgao_id;

        return new NotaTecnicaDashboardResource(
            EsialNotaTecnica::query()
                ->with([
                    'posicionamento',
                    'impactoIntensidade',
                    'impactoTipo',
                    'criticos',
                    'meritos',
                    'conclusoes',
                    'proposicoes',
                    'referencias',
                ])
                ->whereHas('posicionamento')
                ->where('esial_usuario_orgao_id', $orgao)
                ->when($request->input('search'), fn ($query) => $query->whereHas('proposicoes', fn ($query) => $query->where(DB::raw('(sigla || numero || ano)'), 'ilike', '%'.$request->input('search').'%')))
                ->when($request->input('filter') !== 'Todos', fn ($query) => $query->whereHas('posicionamento', fn ($query) => $query->where('posicionamento', $request->input('filter'))))
                ->when($request->input('status') !== 'Todos' && $request->input('status') != '2', fn ($query) => $query->where('concluida', $request->input('status')))
                ->when($request->input('status') == '2', fn ($query) => $query->onlyTrashed())
                ->paginate(8)
        );
    }
}
