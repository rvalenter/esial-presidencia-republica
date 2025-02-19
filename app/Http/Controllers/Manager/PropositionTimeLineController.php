<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Manager\PropositionTimeLineResournce;
use App\Models\Manager\EsialGestaoProposicaoGeralAuditoria;
use Illuminate\Http\Request;

class PropositionTimeLineController extends Controller
{
    public function __invoke(int $id): PropositionTimeLineResournce
    {
        return new PropositionTimeLineResournce(EsialGestaoProposicaoGeralAuditoria::where('esial_legislativo_proposicao_id', $id)
            ->with('usuario')
            ->distinct()
            ->orderBy('created_at', 'desc')
            ->get()
        );
    }
}
