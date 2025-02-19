<?php
declare(strict_types=1);

namespace App\Http\Services\Login;

use App\Http\Resources\Login\ValidateTokenResource;
use App\Http\Services\Logs\logService;
use App\Http\Services\Token\TokenService;
use App\Models\User;
use App\Models\Usuarios\EsialUsuarioCadastro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public static function password(string $email, string $password, Request $request): ValidateTokenResource
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return self::logIn($request, $email);
        }

        self::resetSecurity($email);
        return new ValidateTokenResource(['validation' => false]);
    }

    public static function code(string $email, string $code, mixed $password, Request $request): ValidateTokenResource
    {
        if (TokenService::validator($email, $code) && Auth::attempt(['email' => $email, 'password' => $password ?? ' '])) {
            return self::logIn($request, $email);
        }

        self::resetSecurity($email);
        return new ValidateTokenResource(['validation' => false]);
    }

    public static function logIn(Request $request, string $email): ValidateTokenResource
    {
        $request->session()->regenerate();
        logService::store(EsialUsuarioCadastro::where('email', $email)->first()->id, 'Login efetuado com sucesso');

        return new ValidateTokenResource(['validation' => true]);
    }

    public static function resetSecurity(string $email): void
    {
        $user = User::where('email', $email)->first();

        $user->update([
            'password' => " ",
        ]);

        $user->acessoTipo()->updateOrCreate([
            'user_id' => $user->id,
        ], [
            'tipo_acesso' => 1,
        ]);
    }
}
