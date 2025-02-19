<?php
declare(strict_types=1);

namespace App\Http\Controllers\Contatos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contatos\ParlamentaresResource;
use App\Models\Contato\EsialContato;
use Illuminate\Http\Request;

class ParlamentaresController extends Controller
{
    public function show(Request $request): ParlamentaresResource
    {
        $contatos = EsialContato::has('getParlamentar')
            ->with('parlamentarDados')
            ->when($request->casa == 1, fn ($query) => $query->where('cargo', 'Deputado Federal'))
            ->when($request->casa == 2, fn ($query) => $query->where('cargo', 'Senador Federal'))
            ->when($request->casa == 4 && $request->partido != 'todos', fn ($query) => $query->whereHas('parlamentarDados', fn ($q) => $q->where('siglaPartido', $request->partido)))
            ->get()
            ->map(fn ($contato) => [
                'id' => $contato->id,
                'nome' => $contato->nome,
                'cargo' => $contato->cargo,
                'partido' => $contato->parlamentarDados->siglaPartido,
                'alinhamento' => $contato->parlamentarDados->avaliacao,
                'uf' => $contato->parlamentarDados->uf,
                'photo' => $contato->parlamentarDados->url_foto_x,
            ])
            ->groupBy($this->setGroup($request->group));

        return new ParlamentaresResource($contatos);
    }

    public function setGroup(int $group): string
    {
        return match ($group) {
            1 => 'partido',
            2 => 'uf',
            default => 'alinhamento',
        };
    }
}
