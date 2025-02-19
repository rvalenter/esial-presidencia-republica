<?php
declare(strict_types=1);

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Http\Resources\Login\GetAutoResource;
use App\Http\Resources\Login\ValidateTokenResource;
use App\Http\Services\Login\LoginService;
use App\Http\Services\Parses\CpfService;
use App\Http\Services\Parses\EmailService;
use App\Http\Services\Token\TokenService;
use App\Models\Usuarios\EsialUsuarioCadastro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Login/Login');
    }

    public function getAuth(Request $request): GetAutoResource
    {
        $user = EsialUsuarioCadastro::where('cpf', CpfService::sanitize($request->cpf))->first();

        if (!$user || blank($user->nome)) {
            return new GetAutoResource(['status' => $user ? 1 : 0, 'email' => null]);
        }

        return new GetAutoResource(['status' => 2, 'email' => EmailService::hiddenMail($user->email)]);
    }

    public function token(Request $request): ValidateTokenResource
    {
        $email = EsialUsuarioCadastro::where('cpf', CpfService::sanitize($request->cpf))->first()->email;

        TokenService::sendToken($email);

        return new ValidateTokenResource(['email' => EmailService::hiddenMail($email)]);
    }

    public function validateToken(Request $request): ValidateTokenResource
    {
        $email = EsialUsuarioCadastro::where('cpf', CpfService::sanitize($request->cpf))
            ->first()
            ->email;

        if ($request->type === 2) {
            return LoginService::password($email, $request->password, $request);
        }
        
        return LoginService::code($email, $request->token, $request->password, $request);
    }


    public function validateEmail(Request $request): ValidateTokenResource
    {
        return new ValidateTokenResource(['validation' => EsialUsuarioCadastro::where('email', $request->email)->exists()]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
