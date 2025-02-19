<?php

namespace App\Http\Services\Manager;

use App\Models\Manager\EsialGestorProposicaoComentarioMencao;

class MentionService
{
    public static function store(array $data, int $commentId): void
    {
        collect($data)->each(function ($mentionContactId) use ($commentId) {
            $mention = new EsialGestorProposicaoComentarioMencao();
            $mention->esial_contato_id = $mentionContactId;
            $mention->esial_gestor_proposicao_comentario_id = $commentId;
            $mention->save();
        });
    }
}
