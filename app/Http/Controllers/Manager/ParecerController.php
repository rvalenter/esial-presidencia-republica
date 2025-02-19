<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Manager\PropositionExplanationResource;
use App\Models\Manager\EsialGestaoProposicaoParecerRelator;
use Illuminate\Http\Request;

class ParecerController extends Controller
{
    public function store(Request $request)
    {
        EsialGestaoProposicaoParecerRelator::create(
            [
                'esial_legislativo_proposicao_id' => $request->id,
                'esial_legislativo_colegiado_id' => $request->idCommission,
                'parecer' => $request->parecer,
                'tipo' => $request->tipo_parecer,
            ]
        );
    }

    public function show(int $id, int $comiteid)
    {
        return new PropositionExplanationResource(
            EsialGestaoProposicaoParecerRelator::where('esial_legislativo_proposicao_id', $id)
                ->where('esial_legislativo_colegiado_id', $comiteid)
                ->latest('id')
                ->first()
        );
    }

    public function history(int $id, int $comiteid)
    {
        return new PropositionExplanationResource(
            EsialGestaoProposicaoParecerRelator::where('esial_legislativo_proposicao_id', $id)
                ->where('esial_legislativo_colegiado_id', $comiteid)
                ->get()
        );
    }
}
