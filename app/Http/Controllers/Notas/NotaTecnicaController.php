<?php
declare(strict_types=1);

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nota\NotaTecnicaRequest;
use App\Http\Resources\Notas\NotaTecnicaResource;
use App\Http\Services\Logs\logService;
use App\Http\Services\NotaSei\NotaTecnicaSeiService;
use App\Http\Services\NotaTecnicaReferencia\ReferenciaService;
use App\Http\Services\NotaTecnicaService;
use App\Http\Services\Notification\Notification;
use App\Models\Legislativo\EsialLegislativoProposicao;
use App\Models\Nota\EsialNotaTecnica;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotaTecnicaController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Nota/NotaTecnica');
    }

    public function show(EsialNotaTecnica $notaTecnica): NotaTecnicaResource
    {
        return new NotaTecnicaResource(
            $notaTecnica->load([
                'analises',
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
            ])
        );
    }

    public function showReference(EsialLegislativoProposicao $esialLegislativoProposicao): NotaTecnicaResource
    {
        return new NotaTecnicaResource($esialLegislativoProposicao->load([
            'notaTecnica' => function ($query) {
                $query->where('esial_usuario_orgao_id', Auth::user()->DadosCadastrais->esial_usuario_orgao_id);
            }
        ]));
    }

    public function store(NotaTecnicaRequest $request): NotaTecnicaResource
    {
        return new NotaTecnicaResource($this->storeOperator($request));
    }

    public function storeOperator(NotaTecnicaRequest $request): EsialNotaTecnica
    {
        $user = $request->user ? User::find($request->user)->DadosCadastrais : Auth::user()->DadosCadastrais;

        $nt = EsialNotaTecnica::updateOrCreate([
            'esial_legislativo_proposicao_id' => $request['id'],
            'esial_usuario_orgao_id' => $user->esial_usuario_orgao_id,
        ], [
            'user_id' => $user->id,
            'tipo' => $request['type'] ?? 0,
            'data_referencia' => Carbon::now(),
        ]);

        NotaTecnicaSeiService::store($nt, $request);
        logService::store($user->id, "Nota técnica {$nt->id} distribuida para redigir: $user->id");
        Notification::create($user->id, "Nova Nota Técnica Para Redigir: {$nt->proposicoes->sigla} {$nt->proposicoes->numero}/{$nt->proposicoes->ano}");

        return $nt;
    }

    public function finish(EsialNotaTecnica $notaTecnica): void
    {
        $user = Auth::user()->DadosCadastrais;

        logService::store($user->id, "Nota técnica {$notaTecnica->id} conclída");

        $notaTecnica->update(['concluida' => true]);
    }

    public function compare(int $id): NotaTecnicaResource
    {
        return new NotaTecnicaResource(EsialNotaTecnica::where('esial_legislativo_proposicao_id', $id)
            ->with('orgao', 'posicao', 'conclusoes')
            ->get());
    }

    public function destroy(EsialNotaTecnica $notaTecnica): void
    {
        $notaTecnica->delete();

        if ($notaTecnica->deleted_at != null) {
            $user = Auth::user()->DadosCadastrais;
            logService::store($user->id, "Nota técnica {$notaTecnica->id} excluída");
        }
    }

    public function recycle(int $id): void
    {
        $user = Auth::user()->DadosCadastrais;
        $nota = EsialNotaTecnica::withTrashed()->find($id);
        $nota->restore();
        logService::store($user->id, "Nota técnica {$nota->id} restaurada");
    }
}
