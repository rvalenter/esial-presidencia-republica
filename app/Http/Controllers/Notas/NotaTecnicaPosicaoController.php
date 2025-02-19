<?php
declare(strict_types=1);

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use App\Models\Nota\EsialNotaTecnica;
use App\Models\Nota\EsialNotaTecnicaConclusao;
use App\Models\Nota\EsialNotaTecnicaCritico;
use App\Models\Nota\EsialNotaTecnicaMerito;
use App\Models\Nota\EsialNotaTecnicaReferenciaRelacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotaTecnicaPosicaoController extends Controller
{
    public function store(Request $request): void
    {
        $proposicaoId = EsialNotaTecnica::find($request->id)->esial_legislativo_proposicao_id;

        $nt = EsialNotaTecnica::updateOrCreate([
            'id' => $request->id,
            'esial_usuario_orgao_id' => Auth::user()->DadosCadastrais->esial_usuario_orgao_id,
            'user_id' => Auth::id(),
            'esial_legislativo_proposicao_id' => $proposicaoId,
        ], $request->only([
            'esial_nota_tecnica_posicionamento_id',
            'esial_nota_tecnica_impacto_tipo_id',
            'esial_nota_tecnica_impacto_intensidade_id',
            'impacto_percepcao',
            'urgente',
            'confidencial',
            'data_referencia',
            'posicao_justificativa',
        ]));

        if ($request->has('referencesAdded')) {
            collect($request->input('referencesAdded'))->each(fn ($reference) => $nt->referenciaRelacaoMany()->updateOrCreate([
                'esial_nota_tecnica_id' => $nt->id,
                'esial_nota_tecnica_referencia_preencimento_id' => $reference['id'],
            ], [
                'referencia' => $reference['name'],
                'complemento' => in_array($reference['id'], [4, 6, 7, 8]) ? $reference['complemento'] : null,
                'contexto' => in_array($reference['id'], [4, 6, 7, 8]) ? $reference['complementoDescricao'] ?? null : null,
            ]));
        }
    }

    public function destroy(Request $request): void
    {
        EsialNotaTecnicaReferenciaRelacao::where([
            'esial_nota_tecnica_id' => $request->id,
            'esial_nota_tecnica_referencia_preencimento_id' => $request->reference['id'],
        ])->delete();
    }

    public function storeText(Request $request): void
    {
        $this->setModel($request->working)->create([
            'esial_nota_tecnica_id' => $request->id,
            'conteudo' => $request->texto,
            'user_id' => Auth::id(),
        ]);
    }

    public function updateText(Request $request): void
    {
        $this->setModel($request->working)->updateOrCreate([
            'id' => $request->id['id'] ?? null,
        ], [
            'conteudo' => $request->texto,
            'user_id' => Auth::id(),
            'esial_nota_tecnica_id' => $request->nt,
        ]);
    }

    public function setModel(string $action): EsialNotaTecnicaCritico|EsialNotaTecnicaMerito|EsialNotaTecnicaConclusao
    {
        return [
            2 => new EsialNotaTecnicaCritico(),
            3 => new EsialNotaTecnicaMerito(),
            4 => new EsialNotaTecnicaConclusao(),
        ][$action];
    }
}
