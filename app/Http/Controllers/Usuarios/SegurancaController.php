<?php
declare(strict_types=1);

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Usuarios\SegurancaResource;
use App\Http\Services\Parses\CpfService;
use App\Models\User;
use Illuminate\Http\Request;

class SegurancaController extends Controller
{
    public function store(Request $request): SegurancaResource
    {
        $user = User::find($request->id);

        $user->update([
            'password' => $request->password,
        ]);

        $user->acessoTipo()->updateOrCreate([
            'user_id' => $user->id,
        ], [
            'tipo_acesso' => $request->securityMethod,
        ]);

        return $this->show($request->id);
    }

    public function show(int $id): SegurancaResource
    {
        return new SegurancaResource(User::find($id)->load('acessoTipo'));
    }

    public function cpf(string $cpf): SegurancaResource
    {
        $user = User::where('cpf', CpfService::sanitize($cpf))->first();

        if (!$user->acessoTipo) {
            $user->acessoTipo()->updateOrCreate([
                'user_id' => $user->id,
            ], [
                'tipo_acesso' => 1,
            ]);

            $user = User::where('cpf', CpfService::sanitize($cpf))->first();
        }

        return new SegurancaResource($user->acessoTipo);
    }
}
