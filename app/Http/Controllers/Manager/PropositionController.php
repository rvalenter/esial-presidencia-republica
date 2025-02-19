<?php
declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Manager\PropositionResource;
use App\Models\Manager\EsialGestaoProposicaoGeral;
use App\Models\Manager\EsialGestaoProposicaoGeralAuditoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropositionController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Manager/ManagerPropositions');
    }

    public function store(Request $request): void
    {
        EsialGestaoProposicaoGeralAuditoria::create($request->all());

        EsialGestaoProposicaoGeral::updateOrCreate(
            ['esial_legislativo_proposicao_id' => $request->esial_legislativo_proposicao_id],
            $request->all()
        );
    }

    public function show(int $id): PropositionResource
    {
        return new PropositionResource(
            EsialGestaoProposicaoGeral::where('esial_legislativo_proposicao_id', $id)
                ->first()
        );
    }
}
