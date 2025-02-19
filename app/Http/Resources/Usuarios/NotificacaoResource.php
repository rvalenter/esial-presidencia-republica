<?php

namespace App\Http\Resources\Usuarios;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificacaoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->map(function ($notificacao) {

            $user = User::find($notificacao->responsavel);

            return [
                'id' => $notificacao->id,
                'notificacao' => $notificacao->notificacao,
                'lida' => $notificacao->lida,
                'prioritaria' => $notificacao->prioritaria,
                'data_leitura' => $notificacao->data_leitura,
                'url' => $notificacao->url,
                'responsavel' => $user->name,
                'responsavel_id' => $notificacao->responsavel,
                'responsavel_foto' => collect($user->photo->first())->isNotEmpty() ? $user->photo->first()->file : null,
                'created_at' => Carbon::parse($notificacao->created_at)->format('d/m/Y H:i'),
            ];
        })->all();
    }
}
