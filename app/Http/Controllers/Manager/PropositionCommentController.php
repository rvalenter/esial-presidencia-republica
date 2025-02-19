<?php
declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Manager\PropositionCommentResource;
use App\Http\Services\Manager\MentionService;
use App\Models\Manager\EsialGestorProposicaoComentario;
use Illuminate\Http\Request;

class PropositionCommentController extends Controller
{
    public function store(Request $request)
    {
        $comment = EsialGestorProposicaoComentario::create([
            'esial_legislativo_proposicao_id' => $request->proposition_id,
            'esial_legislativo_colegiado_id' => $request->committee_id,
            'comentario' => $request->comment,
            'restrito' => $request->restricted,
        ]);

        MentionService::store($request->mentions, $comment->id);

        return $this->show($request->proposition_id, $request->committee_id);
    }

    public function show(int $propositionId, int $committeeId)
    {
        return new PropositionCommentResource(EsialGestorProposicaoComentario::where('esial_legislativo_proposicao_id', $propositionId)
            ->where('esial_legislativo_colegiado_id', $committeeId)
            ->get());
    }
}
