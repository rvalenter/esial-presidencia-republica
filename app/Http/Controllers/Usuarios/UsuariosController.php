<?php
declare(strict_types=1);

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Usuarios\UsuarioAcessosResource;
use App\Http\Services\Logs\logService;
use App\Models\Usuarios\EsialUsuarioAcesso;
use App\Models\Usuarios\EsialUsuarioCadastro;
use App\Models\Usuarios\EsialUsuarioPredefinicao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class UsuariosController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Usuarios/Usuarios');
    }

    public function show(string $cpf): Response|RedirectResponse
    {
        $cpfExists = EsialUsuarioCadastro::where('cpf', $cpf)
            ->exists();

        if ($cpfExists) {
            logService::store(auth()->user()->id, 'Consultou acessos do usuário: '.$cpf);

            return Inertia::render('Usuarios/Usuarios', [
                'cpf' => Str::padLeft($cpf, 11, '0'),
            ]);
        }

        return to_route('gestao.usuarios');
    }

    public function store(Request $request): void
    {
        DB::transaction(function () use ($request) {
            $userId = auth()->user()->DadosCadastrais->id;
            $idAcesso = $request->input('idAcesso');
            $idUsuario = $request->input('idUsuario');

            logService::store($userId, "Adicinou acesso $idAcesso ao usuário: $idUsuario");

            EsialUsuarioPredefinicao::where('esial_usuario_cadastro_id', $idUsuario)->delete();

            EsialUsuarioAcesso::updateOrCreate([
                'esial_nivel_acesso_id' => $idAcesso,
                'esial_usuario_cadastro_id' => $idUsuario,
                'responsavel' => auth()->user()->id,
            ]);
        });
    }

    public function acessos(int $idUsuario): UsuarioAcessosResource
    {
        return new UsuarioAcessosResource(
            EsialUsuarioAcesso::where('esial_usuario_cadastro_id', $idUsuario)->get()
        );
    }

    public function destroy(Request $request): void
    {
        $userId = auth()->user()->DadosCadastrais->id;
        $idAcesso = $request->input('idAcesso');
        $idUsuario = $request->input('idUsuario');

        logService::store($userId, "Removeu acesso $idAcesso do usuário: $idUsuario");

        EsialUsuarioAcesso::where('esial_usuario_cadastro_id', $idUsuario)
            ->where('esial_nivel_acesso_id', $idAcesso)
            ->delete();
    }
}
