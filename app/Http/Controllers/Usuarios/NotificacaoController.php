<?php
declare(strict_types=1);

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Usuarios\NotificacaoResource;
use App\Http\Resources\Usuarios\TenhoNotificacaoResource;
use App\Models\Usuarios\EsialUsuarioNotificacao;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    public function show(string $filter): NotificacaoResource
    {
        $notificacoes = auth()->user()->notificacoes()
            ->orderByDesc('prioritaria')
            ->orderByDesc('id')
            ->when($filter !== 'todas', fn ($query) => $query->where('lida', true), fn ($query) => $query->where('lida', false))
            ->get();

        return new NotificacaoResource($notificacoes);
    }

    public function hasNotification(): TenhoNotificacaoResource
    {
        return new TenhoNotificacaoResource(['status' => auth()->user()->notificacoes()->where('lida', false)->exists()]);
    }

    public function archive(Request $request): void
    {
        EsialUsuarioNotificacao::find($request->id)->update([
            'lida' => true,
            'data_leitura' => now(),
        ]);
    }
}
