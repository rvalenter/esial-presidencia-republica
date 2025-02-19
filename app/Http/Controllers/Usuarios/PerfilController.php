<?php
declare(strict_types=1);

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Usuarios\CadastradoResource;
use App\Http\Resources\Usuarios\PhotoResource;
use App\Http\Resources\Usuarios\UsuarioResource;
use App\Http\Services\Logs\logService;
use App\Http\Services\Token\TokenService;
use App\Models\User;
use App\Models\Usuarios\EsialUsuarioCadastro;
use App\Models\Usuarios\EsialUsuarioCargo;
use App\Models\Usuarios\EsialUsuarioOrgao;
use App\Models\Usuarios\EsialUsuarioSetor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PerfilController extends Controller
{
    public function index(?int $id = null): Response
    {
        $userId = auth()->user()->id;

        return Inertia::render('Usuarios/Perfil', [
            'id' => $id ?? $userId,
            'owner' => $userId === $id || is_null($id),
        ]);
    }

    public function show(int $id): mixed
    {
        $user = User::find($id);

        if ($user) {
            return new UsuarioResource([
                'user' => $user->DadosCadastrais
                    ->loadCount('MyUsers')
                    ->load(['Orgao', 'Cargo', 'Setor']),
            ]);
        }

        return new UsuarioResource([
            'user' => EsialUsuarioCadastro::find($id)
                ->load(['Orgao', 'Cargo', 'Setor']),
        ]);
    }

    public function showUser(): UsuarioResource
    {
        $user = auth()->user();

        return new UsuarioResource([
            'user' => $user,
            'photo' => $user->photo->isNotEmpty() ? $user->photo->first()->file : null,
        ]);
    }

    public function updateEmail(Request $request): void
    {
        $validated = $request->validate([
            'id' => 'required|numeric',
            'email' => 'required|email:rfc',
            'code' => 'required|numeric',
        ]);

        if (TokenService::validator($validated['email'], $validated['code'])) {
            $cadastro = EsialUsuarioCadastro::find($request['id']);

            DB::transaction(function () use ($cadastro, $validated) {
                $cadastro->update(['email' => $validated['email']]);
                $cadastro->user()->update(['email' => $validated['email']]);
            });

            logService::store($request['id'], 'Atualizou email');
        } else {
            abort(403, 'Código inválido');
        }
    }

    public function update(Request $request): void
    {
        $cadastro = EsialUsuarioCadastro::find($request->id);

        DB::transaction(function () use ($request, $cadastro) {
            $orgaoId = EsialUsuarioOrgao::where('nome', $request->orgao)->value('id');
            $setorId = EsialUsuarioSetor::where('nome', $request->setor)->value('id');
            $cargoId = EsialUsuarioCargo::where('nome', $request->cargo)->value('id');

            $cadastro->update([
                'nome' => $request->nome,
                'telefone' => $request->telefone,
                'esial_usuario_orgao_id' => $orgaoId,
                'esial_usuario_setor_id' => $setorId,
                'esial_usuario_cargo_id' => $cargoId,
            ]);

            $cadastro->user()->update(['name' => $request->nome]);
        });

        logService::store(auth()->user()->id, 'Atualizou cadastro do usuário: '.$request->id);
    }

    public function sendCodeUpdateEmail(Request $request): void
    {
        $validated = $request->validate(['email' => 'required|email:rfc']);
        TokenService::sendToken($validated['email']);
    }

    public function destroy(int $id): void
    {
        DB::transaction(function () use ($id) {
            $cadastro = EsialUsuarioCadastro::find($id);
            $cadastro->user()->delete();
            $cadastro->delete();
        });

        logService::store(auth()->user()->id, 'Excluiu cadastro do usuário: '.$id);
    }

    public function showPhoto(int $id): PhotoResource
    {
        $user = User::find($id);
        $photo = $user && $user->photo->isNotEmpty() ? $user->photo->first()->file : null;

        return new PhotoResource(['photo' => $photo]);
    }

    public function storePhoto(Request $request): void
    {
        $request->validate(['photo' => 'required|image|max:10000']);

        $user = auth()->user();
        $user->photo()->updateOrCreate(
            ['user_id' => $user->id],
            ['file' => base64_encode(file_get_contents($request->file('photo')))]
        );

        logService::store($user->id, 'Atualizou foto de perfil');
    }

    public function registeredUsers(?string $userSearch = null): CadastradoResource
    {
        $user = auth()->user();
        $userType = $this->userType($user);

        $query = EsialUsuarioCadastro::query()
            ->with('orgaos')
            ->when($userSearch, fn ($q) => $q->where('nome', 'ilike', "%$userSearch%")
                ->orWhere('cpf', 'like', "%$userSearch%")
                ->orWhere('email', 'ilike', "%$userSearch%")
                ->orWhereHas('orgaos', fn ($q) => $q->where('sigla', 'ilike', "%$userSearch%")))
            ->when($userType === 0, fn ($q) => $q->where('responsavel', $user->id))
            ->when($userType === 9, fn ($q) => $q->where('esial_usuario_orgao_id', $user->DadosCadastrais->esial_usuario_orgao_id));

        return new CadastradoResource($query->paginate(5));
    }

    public function userType($user): int
    {
        if ($user->access()->where('esial_nivel_acesso_id', 1)->exists()) {
            return 1;
        }

        if ($user->access()->where('esial_nivel_acesso_id', 9)->exists()) {
            return 9;
        }

        return 0;
    }
}
