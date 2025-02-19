<?php
declare(strict_types=1);

namespace App\Http\Controllers\Acessos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Acessos\GrupoResource;
use App\Http\Services\Logs\logService;
use App\Models\Usuarios\EsialAgrupamentoAcesso;
use App\Models\Usuarios\EsialGrupoAcesso;
use App\Models\Usuarios\EsialUsuarioAcesso;
use App\Models\Usuarios\EsialUsuarioPredefinicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    public function index(): GrupoResource
    {
        return new GrupoResource(EsialGrupoAcesso::with('acessos')->get());
    }

    public function showByUser(?int $uid = null): GrupoResource
    {
        $userGroups = $uid ? EsialUsuarioPredefinicao::where('esial_usuario_cadastro_id', $uid)->latest()->first() : [];

        return new GrupoResource([
            'groups' => EsialGrupoAcesso::with('acessos')->get(),
            'userGroups' => $userGroups,
        ]);
    }

    public function show(string $group): GrupoResource
    {
        $query = EsialGrupoAcesso::query();
        $query->where('nome', env('DB_CONNECTION') === 'pgsql' ? 'ilike' : 'like', '%'.$group.'%');

        return new GrupoResource($query->get());
    }

    public function storeGroupAccess(Request $request): void
    {
        DB::transaction(function () use ($request) {
            $idGroup = EsialGrupoAcesso::where('nome', $request->group)->value('id');

            EsialAgrupamentoAcesso::create([
                'esial_grupo_acesso_id' => $idGroup,
                'esial_nivel_acesso_id' => $request->id,
            ]);

            $this->updateUsersGroupAccess($idGroup);

            logService::store(Auth::id(), 'Adicionou o acesso: '.$request->id.', ao grupo de acesso: '.$request->group);
        });
    }

    public function updateUsersGroupAccess(int $idGroup): void
    {
        EsialUsuarioPredefinicao::where('esial_grupo_acesso_id', $idGroup)->each(function ($default) {
            EsialUsuarioAcesso::where('esial_usuario_cadastro_id', $default->esial_usuario_cadastro_id)->delete();
            $access = EsialAgrupamentoAcesso::where('esial_grupo_acesso_id', $default->esial_grupo_acesso_id)
                ->get(['esial_nivel_acesso_id'])
                ->map(fn ($item) => [
                    'esial_usuario_cadastro_id' => $default->esial_usuario_cadastro_id,
                    'esial_nivel_acesso_id' => $item->esial_nivel_acesso_id,
                    'responsavel' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->all();

            EsialUsuarioAcesso::insert($access);
        });
    }

    public function store(Request $request): void
    {
        $validated = $request->validate(['group' => 'required|string']);
        EsialGrupoAcesso::updateOrCreate(['nome' => $validated['group']]);
        logService::store(Auth::id(), 'Criou novo grupo de acesso: '.$validated['group']);
    }

    public function detroyGroupAccess(Request $request): void
    {
        DB::transaction(function () use ($request) {
            $idGroup = EsialGrupoAcesso::where('nome', $request->group)->value('id');

            EsialAgrupamentoAcesso::where('esial_grupo_acesso_id', $idGroup)
                ->where('esial_nivel_acesso_id', $request->id)
                ->delete();

            $this->updateUsersGroupAccess($idGroup);

            logService::store(Auth::id(), 'Removeu o acesso: '.$request->id.', do grupo de acesso: '.$request->group);
        });
    }

    public function storeUserGroup(Request $request): void
    {
        DB::transaction(function () use ($request) {
            EsialUsuarioPredefinicao::updateOrCreate(
                ['esial_usuario_cadastro_id' => $request->user],
                ['esial_grupo_acesso_id' => $request->group, 'responsavel' => Auth::id()]
            );

            EsialUsuarioAcesso::where('esial_usuario_cadastro_id', $request->user)->delete();

            $access = EsialAgrupamentoAcesso::where('esial_grupo_acesso_id', $request->group)
                ->get(['esial_nivel_acesso_id'])
                ->map(fn ($item) => [
                    'esial_usuario_cadastro_id' => $request->user,
                    'esial_nivel_acesso_id' => $item->esial_nivel_acesso_id,
                    'responsavel' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->all();

            EsialUsuarioAcesso::insert($access);

            logService::store(Auth::id(), 'Adicionou o usuário: '.$request->user.', ao grupo de acesso: '.$request->group);
        });
    }
}
