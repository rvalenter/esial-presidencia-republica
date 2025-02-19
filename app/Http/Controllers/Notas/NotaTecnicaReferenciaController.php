<?php
declare(strict_types=1);

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nota\NotaTecnicaRequest;
use App\Http\Resources\Notas\NotaTecnicaReferenciaResource;
use App\Http\Services\NotaSei\NotaTecnicaSeiService;
use App\Http\Services\NotaTecnicaReferencia\ReferenciaService;
use App\Models\Contato\EsialContato;
use App\Models\Legislativo\EsialLegislativoColegiado;
use App\Models\Nota\EsialNotaTecnica;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotaTecnicaReferenciaController extends Controller
{
    public function store(NotaTecnicaRequest $request): NotaTecnicaReferenciaResource
    {
        $user = auth()->user();

        $data = [
            'esial_legislativo_proposicao_id' => $request['id'],
            'esial_usuario_orgao_id' => $user->DadosCadastrais->esial_usuario_orgao_id,
            'user_id' => $user->id,
            'data_referencia' => Carbon::now(),
            'tipo' => $request['type'],
        ];

        if (! blank($request['referenceToUpdate'])) {
            $nt = EsialNotaTecnica::find($request['referenceToUpdate']);
            $nt->update($data);

            return new NotaTecnicaReferenciaResource($nt);
        }

        $nt = EsialNotaTecnica::create($data);

        NotaTecnicaSeiService::store($nt, $request);

        return new NotaTecnicaReferenciaResource($nt);
    }

    public function destroy(Request $request): void
    {
        EsialNotaTecnica::destroy($request->id);
    }

    public function descriptions(Request $request): NotaTecnicaReferenciaResource
    {
        $query = $request->type['id'] == 4 || $request->type['id'] == 7
            ? EsialLegislativoColegiado::query()->where('sigla', 'ilike', '%'.$request->arg.'%')
            : EsialContato::has('getParlamentar')->has('parlamentarDados')->where('nome', 'ilike', '%'.$request->arg.'%');

        return new NotaTecnicaReferenciaResource($query->distinct()->take(5)->get());
    }
}
