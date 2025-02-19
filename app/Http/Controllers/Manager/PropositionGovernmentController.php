<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Manager\PropositionExplanationResource;
use App\Models\Manager\EsialGestaoProposicaoPosicaoGoverno;
use Illuminate\Http\Request;

class PropositionGovernmentController extends Controller
{
    public function store(Request $request)
    {
        EsialGestaoProposicaoPosicaoGoverno::updateOrCreate(
            [
                'esial_legislativo_proposicao_id' => $request->id,
                'esial_legislativo_colegiado_id' => $request->idCommission,
            ],
            [
                'justificativa' => $request->justificativa,
                'complemento' => $request->complemento,
                'tipo_justificativa' => $request->tipo_justificativa,
            ]
        );
    }

    public function show(int $id, int $comiteid)
    {
        return new PropositionExplanationResource(
            EsialGestaoProposicaoPosicaoGoverno::where('esial_legislativo_proposicao_id', $id)
                ->where('esial_legislativo_colegiado_id', $comiteid)
                ->get()
        );
    }
}
