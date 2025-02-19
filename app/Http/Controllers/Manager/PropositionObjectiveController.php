<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Manager\PropositionExplanationResource;
use App\Models\Manager\EsialGestaoProposicaoObjetivo;
use Illuminate\Http\Request;

class PropositionObjectiveController extends Controller
{
    public function store(Request $request)
    {
        EsialGestaoProposicaoObjetivo::updateOrCreate(
            ['esial_gestao_proposicao_geral_id' => $request->esial_gestao_proposicao_geral_id],
            $request->all()
        );
    }

    public function show(int $id)
    {
        return new PropositionExplanationResource(EsialGestaoProposicaoObjetivo::where('esial_gestao_proposicao_geral_id', $id)->get());
    }
}
