<?php
declare(strict_types=1);

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Usuarios\PerfilDescricaoResource;
use App\Models\Usuarios\EsialUsuarioCadastro;
use App\Models\Usuarios\EsialUsuarioDescricao;
use Illuminate\Http\Request;

class PerfilDescricaoController extends Controller
{
    public function show(int $id): PerfilDescricaoResource
    {
        return new PerfilDescricaoResource(EsialUsuarioCadastro::find($id)->Descricoes->last());
    }

    public function store(Request $request): void
    {
        $validated = $request->validate([
            'descricao' => 'required|string',
            'id' => 'required|integer',
        ]);

        EsialUsuarioDescricao::create([
            'descricao' => $validated['descricao'],
            'esial_usuario_cadastro_id' => $validated['id'],
        ]);
    }
}
