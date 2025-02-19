<?php
declare(strict_types=1);

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Usuarios\NivelAcessoResource;
use App\Models\Usuarios\EsialGrupoAcesso;
use App\Models\Usuarios\EsialNivelAcesso;

class NiveisAcessoController extends Controller
{
    public function index(): NivelAcessoResource
    {
        return new NivelAcessoResource(EsialNivelAcesso::with('usuarios')
            ->orderBy('id')
            ->get());
    }

    public function show(string $group): NivelAcessoResource
    {
        $IdGroup = EsialGrupoAcesso::where('nome', $group)->first()->id;

        return new NivelAcessoResource(EsialNivelAcesso::with(['acessos' => function ($q) use ($IdGroup) {
            $q->where('esial_grupo_acesso_id', $IdGroup);
        }])->get());
    }
}
