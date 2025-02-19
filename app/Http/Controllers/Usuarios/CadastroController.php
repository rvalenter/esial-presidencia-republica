<?php
declare(strict_types=1);

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Http\Requests\Usuarios\CadastroInicialRequest;
use App\Http\Resources\Usuarios\CadastroResource;
use App\Http\Services\AuxCadastro\idService;
use App\Http\Services\Logs\logService;
use App\Http\Services\Notification\Notification;
use App\Http\Services\Parses\CpfService;
use App\Http\Services\Token\TokenService;
use App\Mail\Cadastro\ComunicacaoMail;
use App\Models\User;
use App\Models\Usuarios\EsialUsuarioCadastro;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CadastroController extends Controller
{
    public function show(Request $request): CadastroResource
    {
        $cpf = CpfService::sanitize($request->input('cpf'));
        $cadastro = EsialUsuarioCadastro::where('cpf', $cpf)->get();

        return new CadastroResource(['cadastro' => $cadastro]);
    }

    public function validateUser(CadastroInicialRequest $request): void
    {
        $cpf = CpfService::sanitize($request->cpf);
        $email = EsialUsuarioCadastro::where('cpf', $cpf)->firstOrFail()->email;

        TokenService::sendToken($email);
    }

    public function storeUser(CadastroInicialRequest $request): CadastroResource
    {
        $cpf = CpfService::sanitize($request->cpf);
        $email = EsialUsuarioCadastro::where('cpf', $cpf)->firstOrFail()->email;

        if (TokenService::validator($email, $request->code)) {
            DB::transaction(function () use ($request, $cpf) {
                $user = EsialUsuarioCadastro::updateOrCreate(
                    ['cpf' => $cpf],
                    ['telefone' => $request->cell, 'nome' => Str::title($request->input('name'))]
                )->user()->updateOrCreate(
                    ['cpf' => $cpf],
                    ['name' => Str::title($request->input('name')), 'email_verified_at' => now(), 'password' => '']
                );

                event(new Registered($user));
                Auth::login($user);
            });

            $user = User::where('cpf', $cpf)->first()->id;
            Notification::create($user, 'Seja bem-vindo ao ESIAL: '.Str::title($request->input('name')));
            logService::store($user, 'Cadastro efetuado com sucesso');

            return new CadastroResource(['validation' => true]);
        }

        return new CadastroResource(['validation' => false]);
    }

    public function store(Request $request): void
    {
        $user = auth()->user();
        $cpf = CpfService::sanitize($request->input('cpf'));

        try {
            idService::adjust();

            DB::transaction(function () use ($request, $user, $cpf) {
                EsialUsuarioCadastro::updateOrCreate(
                    ['cpf' => $cpf],
                    [
                        'responsavel' => $user->id,
                        'esial_usuario_orgao_id' => $user->DadosCadastrais()->first()->esial_usuario_orgao_id,
                        'email' => $request->input('email'),
                    ]
                )->user()->updateOrCreate(
                    ['cpf' => $cpf],
                    ['email' => $request->input('email'), 'password' => '']
                );
            });
        } catch (\Exception $e) {
            logService::store($user->DadosCadastrais->id, 'Erro ao solicitar cadastro para o CPF: '.$request->input('cpf'));
        }

        $this->postCreateUser($request, $user);
    }

    public function postCreateUser(Request $request, $user): void
    {
        $cpf = CpfService::sanitize($request->input('cpf'));
        $id = EsialUsuarioCadastro::where('cpf', $cpf)->first();
        $access = new UsuariosController();

        collect([8, 5, 6])->each(function ($acceess) use ($access, $id) {
            $access->store(Request::create('/', 'POST', [
                'idAcesso' => $acceess,
                'idUsuario' => $id->id,
            ]));
        });

        logService::store($user->DadosCadastrais->id, 'Solicitou cadastro para o CPF: '.$request->input('cpf'));

        Mail::to($request->email)
            ->cc(['esial@presidencia.gov.br', $user->email])
            ->send(new ComunicacaoMail());
    }
}
