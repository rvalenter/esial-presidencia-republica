<?php

namespace App\Http\Services\Notification;

use App\Mail\Notificacao\NotificacaoMail;
use App\Models\User;
use App\Models\Usuarios\EsialUsuarioNotificacao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Notification
{
    public static function create(int $userId, string $notificacao, ?string $url = null, bool $prioritaria = false)
    {
        EsialUsuarioNotificacao::create([
            'user_id' => $userId,
            'responsavel' => Auth::user()->id,
            'notificacao' => $notificacao,
            'url' => $url,
            'prioritaria' => $prioritaria,
        ]);

        if ($prioritaria) {
            self::email($userId, $notificacao);
        }
    }

    public static function email(int $uid, string $mensage)
    {
        $user = User::find($uid);
        $email = $user->email;
        $name = $user->name;

        Mail::to($email)->send(new NotificacaoMail($name, $mensage));
    }
}
