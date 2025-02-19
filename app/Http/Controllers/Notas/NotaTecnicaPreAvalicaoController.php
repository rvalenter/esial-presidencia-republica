<?php
declare(strict_types=1);

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use App\Http\Services\Notification\Notification;
use App\Models\Nota\EsialNotaTecnica;
use App\Models\User;
use Illuminate\Http\Request;

class NotaTecnicaPreAvalicaoController extends Controller
{
    public function store(EsialNotaTecnica $notaTecnica): void
    {
        $userId = auth()->id();
        $nt = $notaTecnica->proposicoes;
        $notaTecnica->aprovacoes()->create(['user_id' => $userId]);

        User::whereHas('DadosCadastraisMany', fn ($query) => $query
            ->whereHas('acessos', fn ($query) => $query->where('esial_nivel_acesso_id', 9))
            ->whereHas('orgaos', fn ($query) => $query->where('id', $notaTecnica->esial_usuario_orgao_id)))
            ->get()
            ->each(fn ($manager) => Notification::create($manager->id, "Nota técnica da proposição: {$nt->sigla} {$nt->numero}/{$nt->ano}, enviada para avaliação", null, true));
    }

    public function update(EsialNotaTecnica $notaTecnica, Request $request): void
    {
        $notaTecnica->aprovacoes()->update([
            'observacao' => $request->adjusts,
            'aprovador' => auth()->id(),
        ]);
    }
}
