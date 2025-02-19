<?php
declare(strict_types=1);

namespace App\Http\Controllers\Contatos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contato\ContatoResource;
use App\Http\Services\Image\Image;
use App\Models\Contato\EsialContato;
use App\Models\Contato\EsialContatosGrupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ContatoController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        return Inertia::render('Contatos/Contatos', [
            'parlamentary' => $request->id ?? null,
        ]);
    }

    public function list(int $type, string $search = 'Todos'): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $search = $search === 'Todos' ? '' : $search;

        return ContatoResource::collection(
            EsialContato::query()
                ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('nome', 'ilike', "%$search%")
                        ->orWhere('cargo', 'ilike', "%$search%")
                        ->orWhere('organizacao', 'ilike', "%$search%")
                        ->orWhere('telefone', 'ilike', "%$search%")
                        ->orWhere('celular', 'ilike', "%$search%")
                        ->orWhere('email', 'ilike', "%$search%")
                        ->orWhere('endereco', 'ilike', "%$search%")
                        ->orWhere('observacoes', 'ilike', "%$search%");
                }))
                ->when($type === 1, fn ($query) => $query->whereHas('getParlamentar'))
                ->has('parlamentarDados')
                ->orderBy('nome')
                ->with(['getPhoto', 'getRelationships'])
                ->paginate(30)
        );
    }

    public function listGroup(?string $search = null): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $search = $search === 'Todos' ? '' : $search;

        return ContatoResource::collection(
            EsialContatosGrupo::query()
                ->when($search, fn ($query) => $query->where('nome', env('DB_CONNECTION') === 'pgsql' ? 'ilike' : 'like', "%$search%"))
                ->whereHas('Contatos')
                ->with(['Contatos.photoLink', 'Contatos.partido'])
                ->get(['id', 'nome', 'responsavel', 'esial_legislativo_proposicao_id', 'id_camara'])
                ->unique('nome')
                ->sortBy('nome')
        );
    }

    public function show(int $id): ContatoResource
    {
        return new ContatoResource(
            EsialContato::find($id)->load([
                'getPhoto',
                'getRelationships',
                'getParlamentar',
                'parlamentarDados.votacoes',
                'Bancadas',
            ])
        );
    }

    public function search(string $contact): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ContatoResource::collection(
            EsialContato::query()
                ->where(function ($query) use ($contact) {
                    $query->where('nome', 'ilike', "%$contact%")
                        ->orWhere('cargo', 'ilike', "%$contact%")
                        ->orWhere('organizacao', 'ilike', "%$contact%")
                        ->orWhere('telefone', 'ilike', "%$contact%")
                        ->orWhere('celular', 'ilike', "%$contact%")
                        ->orWhere('email', 'ilike', "%$contact%")
                        ->orWhere('endereco', 'ilike', "%$contact%")
                        ->orWhere('observacoes', 'ilike', "%$contact%");
                })
                ->take(5)
                ->get()
        );
    }

    public function store(Request $request): ContatoResource
    {
        $contato = EsialContato::create([
            'nome' => $request->name,
            'cargo' => $request->role,
            'organizacao' => $request->organization,
            'telefone' => $request->phone,
            'celular' => $request->cellphone,
            'email' => $request->email,
            'endereco' => $request->address,
            'observacoes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        if ($request->photo) {
            $contato->foto()->attach(base64_encode(Image::resize($request->photo->getRealPath())));
        }

        if ($request->related) {
            $contato->relacionamentos()->attach(collect($request->related)->pluck('id'));
        }

        return $this->show($contato->id);
    }

    public function storeGroup(Request $request): ContatoResource
    {
        $group = EsialContatosGrupo::updateOrCreate(
            ['nome' => $request->title],
            [
                'responsavel' => Auth::id(),
                'esial_legislativo_proposicao_id' => $request->legis,
            ]
        );

        $group->relacionamento()->sync($request->contacts);

        return new ContatoResource($group);
    }

    public function update(Request $request): ContatoResource
    {
        $contato = EsialContato::find($request->id);

        $contato->update([
            'nome' => $request->name,
            'cargo' => $request->role,
            'organizacao' => $request->organization,
            'telefone' => $request->phone,
            'celular' => $request->cellphone,
            'email' => $request->email,
            'endereco' => $request->address,
            'observacoes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        if ($request->photo) {
            $contato->foto()->sync(base64_encode(Image::resize($request->photo->getRealPath())));
        }

        if ($request->related) {
            $contato->relacionamentos()->sync(collect($request->related)->pluck('id'));
        }

        return $this->show($contato->id);
    }

    public function destroy(Request $request): void
    {
        EsialContato::find($request->id)->delete();
    }

    public function destroyGroup(Request $request): void
    {
        EsialContatosGrupo::find($request->id)->delete();
    }
}
