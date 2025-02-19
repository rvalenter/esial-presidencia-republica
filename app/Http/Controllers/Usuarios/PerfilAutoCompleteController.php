<?php
declare(strict_types=1);

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Usuarios\PerfilAutoCompleteResource;
use App\Models\Usuarios\EsialUsuarioCargo;
use App\Models\Usuarios\EsialUsuarioOrgao;
use App\Models\Usuarios\EsialUsuarioSetor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PerfilAutoCompleteController extends Controller
{
    public function show(Request $request): PerfilAutoCompleteResource
    {
        return new PerfilAutoCompleteResource($this->setModel($request->type)->where('nome', 'ilike', "%$request->search%")->take(5)->get());
    }

    public function setModel(string $type): Builder
    {
        return match ($type) {
            'orgao' => EsialUsuarioOrgao::query(),
            'setor' => EsialUsuarioSetor::query(),
            default => EsialUsuarioCargo::query(),
        };
    }

    public function store(Request $request): void
    {
        $this->setModel($request->type)->updateOrCreate([
            'nome' => $request->search,
        ]);
    }
}
