<?php
declare(strict_types=1);

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Http\Requests\Usuarios\AcessosRequest;
use App\Http\Resources\Usuarios\AcessosResource;
use App\Models\User;
use App\Models\Usuarios\EsialNivelAcesso;
use Illuminate\Http\Request;

class AcessosController extends Controller
{
    public function index(?string $nome = null): AcessosResource
    {
        return new AcessosResource(
            EsialNivelAcesso::query()
                ->when($nome != 'tudo', fn($query) => $query->where('acesso_nome', 'ilike', "%$nome%"))
                ->orderBy('id')
                ->paginate(8)
        );
    }

    public function show(int $id): AcessosResource
    {
        return new AcessosResource(User::find($id)->DadosCadastrais->acessoNome);
    }

    public function store(AcessosRequest $acessosRequest): void
    {
        EsialNivelAcesso::updateOrCreate(
            ['id' => $acessosRequest->input('id')],
            $acessosRequest->only(['acesso_nome', 'acesso_tipo'])
        );
    }

    public function destroy(Request $request): void
    {
        EsialNivelAcesso::destroy($request->id);
    }
}
