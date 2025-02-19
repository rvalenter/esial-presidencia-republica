<?php
declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Manager\EsialGestorProposicaoComentarioContatoResource;
use App\Models\Contato\EsialContato;

class EsialGestorProposicaoComentarioContatoController extends Controller
{
    public function __invoke(string $name): EsialGestorProposicaoComentarioContatoResource
    {
        return new EsialGestorProposicaoComentarioContatoResource(
            EsialContato::query()
                ->with('parlamentarDados')
                ->where('nome', 'like', "%$name%")
                ->take(5)
                ->orderBy('nome')
                ->distinct('nome')
                ->get()
        );
    }
}
